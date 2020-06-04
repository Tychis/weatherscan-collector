<?php

namespace App\Repositories;

use App\Models\Counties;

class CountyRepository implements CountyInterface
{
    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return Counties::all();
    }

    /**
     * {@inheritdoc}
     */
    public function getIdfromValue($county_name)
    {
        return Counties::where(['county_name' => $county_name])->value('id');
    }
}
