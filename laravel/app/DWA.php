<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DWA extends Model
{
    protected $table = 'dwa';

    public $timestamps = false;

    public function site()
    {
        return $this->belongsTo('App\Site');
    }

    public function machine()
    {
        return $this->belongsTo('App\Machine');
    }

    public function activities()
    {
        return $this->hasMany('App\DWAActivity', 'dwa_id');
    }

    public function fuel()
    {
        return $this->hasMany('App\DWAFuel', 'dwa_id');
    }

    public function fluids()
    {
        return $this->hasMany('App\DWAFluid', 'dwa_id');
    }

    public function filters()
    {
        return $this->hasMany('App\DWAFilter', 'dwa_id');
    }

    public function notes()
    {
        return $this->hasMany('App\DWANote', 'dwa_id');
    }

    //form validation - create daily work activity
    public static $createDWARules = [
        'site_id' => 'required|integer|exists:sites,id',
        'employee_id' => 'required|integer|exists:employees,id',
        'machine_id' => 'required|integer|exists:machines,id',
        'machine_checked' => 'required|in:T,F',
        'damage' => 'required|in:T,F'
    ];
}
