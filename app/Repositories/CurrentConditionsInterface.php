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
}
