<?php

namespace App\Repositories;

use App\Models\AlertHistory;

class AlertHistoryRepository implements AlertHistoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return AlertHistory::all();
    }
}
