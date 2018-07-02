<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdditionalManipulation extends Model
{
    public $timestamps = false;

    public function siteName()
    {
        return $this->belongsTo('App\Site', 'site_id');
    }
}
