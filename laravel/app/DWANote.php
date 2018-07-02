<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DWANote extends Model
{
    protected $table = 'dwa_notes';

    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }

    //form validation - save note
    public static $saveNoteRules = [
        'is_edit' => 'required|in:T,F',
        'dwa_id' => 'required_if:is_edit,T|exists:dwa,id',
        'site_id' => 'required|integer|exists:sites,id',
        'employee_id' => 'required|integer|exists:employees,id',
        'machine_id' => 'required|integer|exists:machines,id',
        'note' => 'required'
    ];
}
