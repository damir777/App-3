<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralType extends Model
{
    public $timestamps = false;

    use SoftDeletes;

    //form validation - insert general type
    public static $insert_rules = [
        'type' => 'required|integer|in:1,2,3,4,5,6,7,8,9',
        'name' => 'required'
    ];
}
