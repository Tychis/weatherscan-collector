<?php

namespace App\Repositories;

use App\Models\Locations;

class LocationRepository implements LocationInterface
{
    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return Locations::all();
    }
}
