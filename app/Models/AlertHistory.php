<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlertHistory extends Model
{
    protected $table = 'alert_history';

    protected $fillable = ['alert_id', 'location_id', 'issue_datetime'];
}
