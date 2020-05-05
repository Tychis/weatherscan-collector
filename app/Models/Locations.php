<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    protected $table = 'locations';
    public $timestamps = false;

    protected $fillable = ['location_name', 'province'];

    public function Alert_History()
    {
        return $this->belongsTo('App/Models/AlertHistory');
    }

    public function Current_Conditions()
    {
        return $this->belongsTo('App/Models/CurrentConditions');
    }
}
