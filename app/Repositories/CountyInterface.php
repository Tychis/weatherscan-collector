<?php

namespace App\Repositories;

interface CountyInterface
{
    /**
     * Get all known locations
     * @return mixed
     */
    public function all();

    /**
     * Get county ID from name
     * @return integer
     */
    public function getIdfromValue($county_name);
}
