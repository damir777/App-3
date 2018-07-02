<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investor extends Model
{
    public $timestamps = false;

    use SoftDeletes;

    //form validation - insert investor
    public static $insert_rules = [
        'name' => 'required',
        'country' => 'required|integer|exists:countries,id',
        'city_id' => 'required|integer|exists:cities,id',
        'city' => 'required_unless:country,1',
        'address' => 'required'
    ];
}
