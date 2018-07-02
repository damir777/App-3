<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DWAFuel extends Model
{
    protected $table = 'dwa_fuel';

    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }

    //form validation - save fuel
    public static $saveFuelRules = [
        'site_id' => 'required|integer|exists:sites,id',
        'employee_id' => 'required|integer|exists:employees,id',
        'machine_id' => 'required|integer|exists:machines,id',
        'quantity' => 'required|integer'
    ];
}
