<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteManipulation extends Model
{
    public $timestamps = false;

    public function site()
    {
        return $this->belongsTo('App\Site');
    }

    public function machine()
    {
        return $this->belongsTo('App\Machine', 'resource_id');
    }

    public function tool()
    {
        return $this->belongsTo('App\Tool', 'resource_id');
    }

    public function equipment()
    {
        return $this->belongsTo('App\Equipment', 'resource_id');
    }

    public function vehicle()
    {
        return $this->belongsTo('App\Vehicle', 'resource_id');
    }

    public function employee()
    {
        return $this->belongsTo('App\Employee', 'resource_id');
    }
}
