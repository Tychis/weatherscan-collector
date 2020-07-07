<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XMLSearchURLs extends Model
{
    protected $table = 'xml_search_list';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['url', 'province'];
}
