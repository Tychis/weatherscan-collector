<?php

namespace App\Listeners;

use App\Events\AlertNotify;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AlertNotifyListener
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
     * @param  AlertNotify  $event
     * @return void
     */
    public function handle(AlertNotify $event)
    {
        //
    }
}
