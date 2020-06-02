<?php

namespace App\Repositories;

interface ATOMUrlsInterface
{
    /**
     * Get all ATOM URLs generated from Environment Canada.
     * @return mixed
     */
    public function all();
}
