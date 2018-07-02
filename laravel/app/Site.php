<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    //protected $table = 'sites';

    public $timestamps = false;

    use SoftDeletes;

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function investor()
    {
        return $this->belongsTo('App\Investor');
    }

    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    //form validation - insert/update site
    public static function validateSiteForm($id = false)
    {
        $rules = [
            'code' => 'required|integer',
            'name' => 'required',
            'country' => 'required|integer|exists:countries,id',
            'city_id' => 'required|integer|exists:cities,id',
            'city' => 'required_unless:country,1',
            'address' => 'required',
            'investor' => 'required|integer|exists:investors,id',
            'start_date' => 'required|custom_date',
            'plan_end_date' => 'required|custom_date',
            'end_date' => 'nullable|custom_date',
            'project_manager' => 'required|integer|exists:employees,id',
            'status' => 'required|integer|exists:statuses,id'
        ];

        if ($id)
        {
            $rules['id'] = 'required|integer|exists:sites,id';
        }

        return $rules;
    }
}
