<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParkingManipulation extends Model
{
    public $timestamps = false;

    public function parking()
    {
        return $this->belongsTo('App\Parking');
    }
}
