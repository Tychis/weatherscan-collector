<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use App\Models\XMLSearchURLs;

class ClearURLs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wscan:clearlist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all URLs in the database so they can be re-added. Use if you want a more limited selection of provinces or simply want to start over.';

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
        $this->comment('Truncating Environment Canada Alerts URL List...');
        try {
            // Very simple truncation of the entire table so we can start from scratch
            XMLSearchURLs::truncate();
        } catch (Throwable $e) {
            report($e);
            $this->error('The list could not be cleared!');
            return false;
        }
        $this->info('The Environment Canada Alerts URL list is now empty.');
    }
}
