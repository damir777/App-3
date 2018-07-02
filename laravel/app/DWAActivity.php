<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class DWAActivity extends Model
{
    protected $table = 'dwa_activities';

    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }

    public function tool()
    {
        return $this->belongsTo('App\Tool');
    }

    public function activity()
    {
        return $this->belongsTo('App\GeneralType');
    }

    //form validation - save activity
    public static function saveActivityRules()
    {
        $rules = [
            'is_edit' => 'required|in:T,F',
            'dwa_id' => 'required_if:is_edit,T|exists:dwa,id',
            'site_id' => 'required|integer|exists:sites,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'machine_id' => 'required|integer|exists:machines,id',
            'start_time' => 'required|time',
            'end_time' => 'required|time',
            'tool_id' => 'required|integer',
            'activity' => ['required', 'integer', Rule::exists('general_types', 'id')->where(function ($query) {
                $query->where('type', 9); })],
            'start_working_hours' => 'required|integer',
            'end_working_hours' => 'required|integer'
        ];

        return $rules;
    }

    //form validation - end time
    public static $end_time = [
        'end_time' => 'end_time'
    ];

    //form validation - end working hours
    public static $end_working_hours = [
        'end_working_hours' => 'end_working_hours'
    ];
}
