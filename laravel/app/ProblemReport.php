<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProblemReport extends Model
{
    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }

    public function seenEmployee()
    {
        return $this->belongsTo('App\Employee', 'seen_employee_id');
    }

    //form validation - save problem
    public static $saveProblemRules = [
        'description' => 'required'
    ];
}
