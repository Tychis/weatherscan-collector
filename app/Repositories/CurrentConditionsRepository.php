<?php

namespace App\Repositories;

use App\Models\CurrentConditions;

class CurrentConditionsRepository implements CurrentConditionsInterface
{
    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return CurrentConditions::select('id', 'alert_id', 'location_id', 'issue_datetime')->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getAlerts()
    {
        return CurrentConditions::where('alert_id', '!=', 1)->select('id', 'alert_id', 'location_id', 'issue_datetime')->get();
    }

    /**
     * {@inheritdoc}
     */
    public function getAlertIDsOnly()
    {
        return CurrentConditions::where('alert_id', '!=', 1)->select('id')->get();
    }
}
