<?php

use Illuminate\Database\Seeder;

use App\Models\AlertType;

class DefaultAlert extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // We want to be sure this doesn't exist yet, and then set the "No Alerts in effect" alert as the first row as a basis for our automated alert discovery process
        $default_alert = AlertType::firstOrNew(['alert_title' => 'No alerts in effect', 'alert_type' => 'Statement', 'state' => 2]);
        $default_alert->save();
    }
}
