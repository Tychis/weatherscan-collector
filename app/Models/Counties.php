<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Counties extends Model
{
    protected $table = 'counties';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['county_name'];

    public function Location()
    {
        return $this->belongsTo('App\Models\Locations');
    }
}
