<?php

namespace App\Repositories;

use App\Models\AlertType;

class AlertTypeRepository implements AlertTypeInterface
{
    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return AlertType::all();
    }
}
