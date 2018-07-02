<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class DWAFluid extends Model
{
    protected $table = 'dwa_fluids';

    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }

    public function component()
    {
        return $this->belongsTo('App\MachineComponent');
    }

    //form validation - save fluid
    public static function validateSaveFluid()
    {
        $rules = [
            'site_id' => 'required|integer|exists:sites,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'machine_id' => 'required|integer|exists:machines,id',
            'component' => ['required', 'integer',
                Rule::exists('machine_components', 'id')->where(function ($query) { $query->where('fluid', 'T'); })],
            'quantity' => 'required|integer'
        ];

        return $rules;
    }
}
