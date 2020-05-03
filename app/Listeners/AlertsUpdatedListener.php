<?php

namespace App\Listeners;

use App\Events\AlertsUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\AlertHistory;

class AlertsUpdatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AlertsUpdated  $event
     * @return void
     */
    public function handle(AlertsUpdated $event)
    {
        
    }
}
