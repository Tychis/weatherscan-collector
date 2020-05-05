<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AlertHistory;

class FeedTest extends Controller
{
    public function test2() {
        $alert = AlertHistory::get();
        event(new \App\Events\AlertsUpdated($alert));
    }
}
