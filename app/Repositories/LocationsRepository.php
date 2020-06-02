<?php

namespace App\Repositories;

use App\Models\Locations;

class LocationsRepository implements LocationsInterface
{
    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return Locations::all();
    }
}
