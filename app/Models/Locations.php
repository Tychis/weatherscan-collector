<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    protected $table = 'locations';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['location_name', 'province', 'county_id', 'is_county'];

    public function Alert_History()
    {
        return $this->belongsTo('App\Models\AlertHistory');
    }

    public function Current_Conditions()
    {
        return $this->belongsTo('App\Models\CurrentConditions');
    }

    public function County()
    {
        return $this->hasOne('App\Models\Counties');
    }
}
