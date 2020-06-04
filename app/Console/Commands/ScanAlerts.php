<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\{Collection, Arr, Str};
use App\Models\{XMLSearchURLs, Locations, AlertType, AlertHistory, CurrentConditions, Counties};
use App\Repositories\{AlertHistoryInterface, AlertHistoryRepository, AlertTypeInterface, ATOMUrlsInterface, CurrentConditionsInterface, LocationInterface, CountyInterface};
use Carbon\Carbon;

class ScanAlerts extends Command
{
    private $alertHistory;
    private $alertType;
    private $atomURL;
    private $lastConditions;
    private $locationRepo;
    private $countyRepo;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wscan:scanalerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse through all of the XML URLs stored in xml_search_list and take action.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AlertHistoryInterface $alertHistory, AlertTypeInterface $alertType, ATOMUrlsInterface $atomURL, CurrentConditionsInterface $lastConditions, LocationInterface $locationRepo, CountyInterface $countyRepo)
    {
        parent::__construct();
        $this->alertHistory = $alertHistory;
        $this->alertType = $alertType;
        $this->atomURL = $atomURL;
        $this->lastConditions = $lastConditions;
        $this->locationRepo = $locationRepo;
        $this->countyRepo = $countyRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Create a collection of alert history from `alert_history`, alerts from `alert_dict` and locations from `locations`
        $alert_dictionary = $this->alertType->all();
        $locations = $this->locationRepo->all();
        $counties = $this->countyRepo->all();
        $alert_history = $this->alertHistory->all();
        // To populate with the IDs of affected tables, later
        $new_alert_ids = collect();
        $previous_alert_ids = $this->lastConditions->getAlertIDsOnly();
        // This will be used to track the number of alerts in the scanned zones
        $alerts = 0;
        $this->comment('Checking that we have URLs to scan...');
        // If there's no URLs in the database, alert the administrator and end the process
        if (XMLSearchURLs::count() == 0) {
            $this->error('There were no URLs to scan. Please run [php artisan wscan:generateurls] first. Add -h to the end of the command for further instructions, such as limiting the number of provinces to scan.');
            return 0;
        }
        // Get a collection of the URLs
        $urls = $this->atomURL->all();
        $this->info('Beginning Environment Canada Alerts Scan!');
        // Create progress bar for the CLI, which inform the task administrator of the time elapsed, items complete, alerts identified and total items in real time
        $progBar = $this->output->createProgressBar(count($urls));
        $progBar->setFormat('%current%/%max% [%bar%] %percent:3s%% || Elapsed: %elapsed:6s% || %message%');
        $progBar->setMessage("Alerts: 0");
        $progBar->start();
        // Loop through URL collection
        foreach ($urls as $url) {
            // Individual URL of the XML feed
            $feed = \FeedReader::read($url->url);
            // Start a loop parser through the XML collection
            $max = $feed->get_item_quantity();
            for ($x = 0; $x < $max; $x++):
                    $item = $feed->get_item($x);
                    $county_name = str_replace(" - Weather Alert - Environment Canada", "", $feed->get_title($x));
                    if(empty($counties->contains('county_name', $county_name)))
                    {
                        $new_county = Counties::firstOrCreate(
                            ['county_name' => $county_name]
                        );
                        $county_id = $new_county->id;
                    } else {
                        $county_id = $counties->where('county_name', $county_name)->first()->id;
                    }
            // Get the alert status from the collection. Element 0 is the alert itself, element 1 is the location
            $alert_array = explode(",", $item->get_title());
            if(empty($alert_dictionary->contains('alert_title', $alert_array[0])))
            {
                // If the alert is not in the `AlertType` table, add it
                try {
                    $alert_detail = self::defineAlertType($alert_array[0]);
                    AlertType::firstOrCreate(
                        ['alert_title' => $alert_array[0]],
                        ['alert_type' => $alert_detail['alert_type'], 'state' => $alert_detail['state']]
                    );
                    unset($alert_detail);
                    // Repopulate the alert dictionary collection
                    $alert_dictionary = $this->alertType->all();
                } catch (Throwable $e) {
                    report($e);
                    $this->error('There was an error while inserting the alert type.');
                    return false;
                }
            }
            if(empty($locations->contains('location_name', trim($alert_array[1]))))
            {
                // If the alert is not in the `AlertType` table, add it
                try {

                    if(trim($alert_array[1]) != $county_name)
                    {
                        Locations::firstOrCreate(
                            ['location_name' => trim($alert_array[1])],
                            ['province' => $url->province, 'county_id' => $county_id]
                        );
                    } else {
                        Locations::firstOrCreate(
                            ['location_name' => trim($alert_array[1])],
                            ['province' => $url->province, 'county_id' => $county_id, 'is_county' => 1]
                        );
                    }
                    // Repopulate the alert dictionary collection
                    $locations = $this->locationRepo->all();
                } catch (Throwable $e) {
                    report($e);
                    $this->error('There was an error while inserting the location.');
                    return false;
                }
            } elseif($locations->where('location_name', trim($alert_array[1]))->first()->county_id == '')
            {
                $location_id = $locations->where('location_name', trim($alert_array[1]))->first()->id;
                if(trim($alert_array[1]) != $county_name)
                {
                    Locations::where(['id' => $location_id])->update(['county_id' => $county_id]);
                } else {
                    Locations::where(['id' => $location_id])->update(['county_id' => $county_id, 'is_county' => 1]);
                }
            }
            // Get the "issue date" from the collection
            $issue_date = Carbon::parse($item->get_date());
            $alert_id = $alert_dictionary->where('alert_title', $alert_array[0])->first()->id;
            $location_id = $locations->where('location_name', trim($alert_array[1]))->first()->id;
            // Add the current alert to the history, but only if it doesn't already exist.
            if($alert_history->where(['alert_id' => $alert_id, 'location_id' => $location_id, 'issue_datetime' => $issue_date])->count() == 0) {
                AlertHistory::firstOrCreate(
                    ['issue_datetime' => $issue_date, 'alert_id' => $alert_id, 'location_id' => $location_id]
                );
                $new_condition = CurrentConditions::updateOrCreate(['location_id' => $location_id], ['alert_id' => $alert_id, 'issue_datetime' => $issue_date]);
                if($alert_id != 1)
                {
                    $new_alert_ids->push($new_condition->id);
                }
            }
            // Increment the count of Alerts passed back to the CLI
            if ($item->get_content() != "No alerts in effect") {
                $alerts++;
                $progBar->setMessage("Alerts: $alerts");
            }
            // Advance the progress bar
            $progBar->advance();
            endfor;
        }
        // The task is complete, so we've reached 100%, notify the task administrator
        $progBar->setMessage("Alerts: $alerts | Finishing...");
        // Send it to the cleaners to ensure the stale alerts are removed. When the alert has been terminated, we don't receive anything for each individual town/city within a given county other than an alert ended statement. It vanishes from the XMLs once the alert ended status expires, so we can't reset it back to "No alerts in effect" automatically.
        $difference = $previous_alert_ids->pluck('id')->diff($new_alert_ids);
        //
        foreach($difference as $diff) {
            CurrentConditions::where(['id' => $diff])->update(['alert_id' => 1]);
        }
        $progBar->finish();
        $this->info("Alerts: $alerts | Scan completed");
    }

    private function initialStateToArray($collection_item)
    {
        $i = 0;
        foreach($collection_item as $data)
        {
            $collection = array(
                'id' => $data->id,
                'alert_id' => $data->alert_id,
                'location_id' => $data->location_id,
                'issue_datetime' => $data->issue_datetime
            );
        }
        return $collection;
    }

    private function defineAlertType($str)
    {
        //TODO: Improve and optimize. This may not capture all possibilities.
        if(Str::contains($str, 'No alerts in effect') == True) {
            // Make sure we just record this as a "statement" as there is nothing going on
            $response = Arr::add(['alert_type' => 'Statement', 'state' => 2], 'alert_type', 'Statement');
        } elseif(Str::contains($str, 'IN EFFECT') == True) {
            // Make sure we properly categorize active alerts with the phrases `WARNING`, `WATCH`, and `STATEMENT` in the database
            if(Str::contains($str, 'WARNING') == True) {
                $response = Arr::add(['alert_type' => 'Warning', 'state' => 1], 'alert_type', 'Warning');
            } elseif(Str::contains($str, 'WATCH') == True) {
                $response = Arr::add(['alert_type' => 'Watch', 'state' => 1], 'alert_type', 'Watch');
            } elseif(Str::contains($str, 'STATEMENT') == True) {
                $response = Arr::add(['alert_type' => 'Statement', 'state' => 2], 'alert_type', 'Statement');
            } else {
                $response = Arr::add(['alert_type' => 'Other', 'state' => 2], 'alert_type',  'Other');
            }
        } elseif(Str::contains($str, 'ENDED') == True) {
            // Make sure we properly categorize recently terminated alerts with the phrases `WARNING`, `WATCH`, and `STATEMENT` in the database
            if(Str::contains($str, 'WARNING') == True) {
                $response = Arr::add(['alert_type' => 'Warning', 'state' => 0], 'alert_type',  'Warning');
            } elseif(Str::contains($str, 'WATCH') == True) {
                $response = Arr::add(['alert_type' => 'Watch', 'state' => 0], 'alert_type', 'Watch');
            } else {
                $response = Arr::add(['alert_type' => 'Statement', 'state' => 2], 'alert_type', 'Statement');
            }
        } else {
            // Catch all if anything else appears that we haven't experienced before
            $response = Arr::add(['alert_type' => 'Other', 'state' => 2], 'alert_type',  'Other');
        }
        return $response;
    }
}
