<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CurrentConditions;

class MainController extends Controller
{
    /**
     * First page accessed upon entry to the website
     *
     * @return Factory|View
     */
    public function EntryPage()
    {
        $alerts = CurrentConditions::with(['alert_type', 'alert_location'])->where('alert_id', '!=', 1)->get();
        return view('home', compact('alerts'));
    }
}
