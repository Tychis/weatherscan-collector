<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use App\Models\XMLSearchURLs;

class Generate_AlertXML_URLs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wscan:generateurls {--p|province=all : The ISO code of the province. Acceptable options: BC, AB, SK, MB, ON, QC, NB, NS, NL, PEI, NU, NT, YT}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loop through Environment Canada alert XMLs and confirm their existence. Add new items as necessary. Arguments: --province or -p [ISO_CODE] to add the alerts for a single province by its ISO 3166-2:CA code. For example, wscan:generatexml -p BC. Running this command a second time with a different province will add additional regions to the list to scan. Acceptable options: BC, AB, SK, MB, ON, QC, NB, NS, NL, PEI, NU, NT, YT';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $request_client)
    {
        parent::__construct();
        $this->request_client = $request_client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get all options passed to the interpreter
        $options = $this->options();
        $this->comment('Beginning Environment Canada Alerts URL generation...');
        if ($options["province"] == 'all') {
            // If default is used, pass all provinces to the handler
            $this->comment('Analyzing ALL provinces...');
            $provinces = array('bc','ab','sk','mb','on','qc','nb','ns','nl','pei','nu','nt','yt');
        } else {
            // If a province is provided, create an array with only that province. Note that Environment Canada uses "pei" in their xml title, so we need to add this in separately if someone passes "pe". We also make sure the entire thing is lower case to prevent any blockers due to case sensitivity during the page query.
            $this->comment('Queueing ' . $options["province"] . ' for scan...');
            $provinces = array(strtolower(preg_replace("/\\bPE\\b/", "PEI", $options["province"])));
        }

        foreach ($provinces as $province) {
            $i = 1; // Each time the province changes, make sure the index starts at "1"
            $continue = 1; // Each XML has a numerical index in the URL, count up until we find a 404 error, then if applicable, switch to the next province
            $internal_i = 0; // Just tracking how many entries we added

            while ($continue == 1) {
                $url_base = 'https://weather.gc.ca/rss/battleboard/' . $province . $i . '_e.xml';
                try {
                    $url = $url_base;
                    $response = $this->request_client->get($url, ['http_errors' => false]);
                    $status = $response->getStatusCode();
                } catch (\GuzzleHttp\Exception\ClientException $e) {
                    $this->error('An error has occurred:' . $e->getMessage());
                } finally {
                    if ($status == 404) {
                        // Hard coded for now because for some reason, there is a gap in the Environment Canada numbering
                        if ($url != 'https://weather.gc.ca/rss/battleboard/qc37_e.xml') {
                            $continue = 0;
                        }
                    } elseif ($status == 200) {
                        try {
                            $flight = XMLSearchURLs::firstOrCreate(
                                ['url' => $url],
                                ['province' => strtoupper($province)]);
                            $this->output->write('.', false);
                            $internal_i++;
                        } catch (Throwable $e) {
                            report($e);
                            $this->error('There was an error while inserting ' . $url);
                            return false;
                        }
                    }
                }
                $i++;
            }
            $this->info('Completed URL insertion for ' . strtoupper($province));
        }
        $this->info("The scan is complete!");
        return 0;
    }
}
