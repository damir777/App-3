<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manufacturer extends Model
{
    public $timestamps = false;

    use SoftDeletes;

    //form validation - insert manufacturer
    public static $insert_rules = [
        'name' => 'required'
    ];
}
