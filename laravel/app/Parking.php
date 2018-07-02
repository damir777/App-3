<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parking extends Model
{
    protected $table = 'parking';

    public $timestamps = false;

    use SoftDeletes;

    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    //form validation - insert/update parking
    public static function validateParkingForm($id = false)
    {
        $rules = [
            'name' => 'required',
            'address' => 'required',
            'status' => 'required|integer|exists:statuses,id'
        ];

        if ($id)
        {
            $rules['id'] = 'required|integer|exists:parking,id';
        }

        return $rules;
    }
}
