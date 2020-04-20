<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlertDict extends Model
{
    protected $table = 'alert_dict';
    public $timestamps = false;

    protected $fillable = ['alert_title', 'alert_type', 'state'];
}
