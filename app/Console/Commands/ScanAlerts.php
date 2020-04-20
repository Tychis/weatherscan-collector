<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\{Collection, Arr, Str};
use App\Models\{XMLSearchURLs, Locations, AlertDict, AlertHistory};
use Carbon\Carbon;

class ScanAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wscan:scan';

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
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Create a collection of alerts from `alert_dict` and locations from `locations`
        $alert_dictionary = AlertDict::get();
        $locations = Locations::get();
        // This will be used to track the number of alerts in the scanned zones
        $alerts = 0;
        $this->comment('Checking that we have URLs to scan...');
        // If there's no URLs in the database, alert the administrator and end the process
        if (XMLSearchURLs::count() == 0) {
            $this->error('There were no URLs to scan. Please run [php artisan wscan:generateurls] first. Add -h to the end of the command for further instructions, such as limiting the number of provinces to scan.');
            return 0;
        }
        // Get a collection of the URLs
        $urls = XMLSearchURLs::get();
        $this->info('Beginning Environment Canada Alerts Scan!');
        // Create progress bar for the CLI
        $progBar = $this->output->createProgressBar(count($urls));
        $progBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% || Elapsed: %elapsed:6s% || %message%');
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
            // Get the alert status from the collection. Element 0 is the alert itself, element 1 is the location
            $alert_array = explode(",", $item->get_title());
            if(empty($alert_dictionary->contains('alert_title', $alert_array[0])))
            {
                // If the alert is not in the `AlertDict` table, add it
                try {
                    $alert_detail = self::defineAlertType($alert_array[0]);
                    AlertDict::firstOrCreate(
                        ['alert_title' => $alert_array[0]],
                        ['alert_type' => $alert_detail['alert_type'], 'state' => $alert_detail['state']]
                    );
                    unset($alert_detail);
                    // Repopulate the alert dictionary collection
                    $alert_dictionary = AlertDict::get();
                } catch (Throwable $e) {
                    report($e);
                    $this->error('There was an error while inserting the alert type.');
                    return false;
                }
            }
            if(empty($locations->contains('location_name', $alert_array[1])))
            {
                // If the alert is not in the `AlertDict` table, add it
                try {
                    Locations::firstOrCreate(
                        ['location_name' => trim($alert_array[1])],
                        ['province' => $url->province]
                    );
                    // Repopulate the alert dictionary collection
                    $locations = Locations::get();
                } catch (Throwable $e) {
                    report($e);
                    $this->error('There was an error while inserting the location.');
                    return false;
                }
            }
            // Get the "issue date" from the collection
            $issue_date = $item->get_date();
            $alert_id = AlertDict::where('alert_title', $alert_array[0])->value('id'); // We don't want to make constant round trips to the database, but there is an issue with the resulting array when we take action on the Collection , so this is a temporary solution
            $location_id = Locations::where('location_name', trim($alert_array[1]))->value('id'); // We don't want to make constant round trips to the database, but there is an issue with the resulting array when we take action on the Collection, so this is a temporary solution
            // Add the current alert to the history
            if(AlertHistory::where(['alert_id' => $alert_id, 'location_id' => $location_id, 'issue_datetime' => Carbon::parse($issue_date)])->count() == 0) {
                $hist = new AlertHistory;
                $hist->alert_id = $alert_id;
                $hist->location_id = $location_id;
                $hist->issue_datetime = Carbon::parse($issue_date);
                $hist->save();
            }
            if ($item->get_content() != "No alerts in effect") {
                $alerts++;
                $progBar->setMessage("Alerts: $alerts");
            }
            $progBar->advance();
            endfor;
        }
        $progBar->finish();
        $this->info('Scan completed');
    }

    private static function defineAlertType($str)
    {
        //TODO: Improve and optimize. This may not capture all possibilities.
        if(Str::contains($str, 'No alerts in effect') == True) {
            $response = Arr::add(['alert_type' => 'Statement', 'state' => 2], 'alert_type', 'Statement');
        } elseif(Str::contains($str, 'IN EFFECT') == True) {
            if(Str::contains($str, 'WARNING') == True) {
                $response = Arr::add(['alert_type' => 'Warning', 'state' => 1], 'alert_type', 'Warning');
            } elseif(Str::contains($str, 'WATCH') == True) {
                $response = Arr::add(['alert_type' => 'Watch', 'state' => 1], 'alert_type', 'Watch');
            } elseif(Str::contains($str, 'STATEMENT') == True) {
                $response = Arr::add(['alert_type' => 'Statement', 'state' => 2], 'alert_type', 'Statement');
            }
        } elseif(Str::contains($str, 'ENDED') == True) {
            if(Str::contains($str, 'WARNING') == True) {
                $response = Arr::add(['alert_type' => 'Warning', 'state' => 0], 'alert_type',  'Warning');
            } elseif(Str::contains($str, 'WATCH') == True) {
                $response = Arr::add(['alert_type' => 'Watch', 'state' => 0], 'alert_type', 'Watch');
            }
        }
        return $response;
    }
}
