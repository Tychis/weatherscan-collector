<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlertType extends Model
{
    protected $table = 'alert_dict';
    public $timestamps = false;

    protected $fillable = ['alert_title', 'alert_type', 'state'];

    public function Alert_History()
    {
        return $this->belongsTo('App/Models/AlertHistory');
    }
}
