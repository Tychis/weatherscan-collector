<?php

namespace App\Repositories;

interface CurrentConditionsInterface
{
    /**
     * Get all current conditions
     * @return mixed
     */
    public function all();

    /**
     * Get all current warnings/watches/statements
     * @return mixed
     */
    public function getAlerts();

    /**
     * Get the IDs of the alerts in the Current Conditions index, only, for comparison
     * @return mixed
     */
    public function getAlertIDsOnly();
}
