<?php

namespace App\Repositories;

interface LocationInterface
{
    /**
     * Get all known locations
     * @return mixed
     */
    public function all();
}
