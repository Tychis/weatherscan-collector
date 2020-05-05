<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrentConditions extends Model
{
    protected $table = 'current_conditions';

    protected $fillable = ['alert_id', 'location_id', 'issue_datetime'];

    public function alert_type()
    {
        return $this->hasOne('App\Models\AlertType', 'id', 'alert_id');
    }

    public function alert_location()
    {
        return $this->hasOne('App\Models\Locations', 'id', 'location_id');
    }
}
