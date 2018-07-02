<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Vehicle extends Model
{
    public $timestamps = false;

    use SoftDeletes;

    public function manufacturer()
    {
        return $this->belongsTo('App\Manufacturer');
    }

    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    //form validation - insert/update vehicle
    public static function validateVehicleForm($id = false)
    {
        $rules = [
            'code' => 'required|integer',
            'manufacturer' => 'required|integer|exists:manufacturers,id',
            'name' => 'required',
            'model' => 'required',
            'manufacture_year' => 'required|year',
            'mass' => 'required|integer',
            'type' => ['required', Rule::exists('general_types', 'id')->where(function ($query) { $query->where('type', 4); })],
            'seats_number' => 'required|integer',
            'chassis_number' => 'required',
            'fuel_type' => ['required', Rule::exists('general_types', 'id')->where(function ($query) { $query->where('type', 6); })],
            'purchase_date' => 'required|custom_date',
            'sale_date' => 'nullable|custom_date',
            'start_mileage' => 'required|integer',
            'end_working_hours' => 'nullable|integer',
            'register_number' => 'required',
            'register_date' => 'required|custom_date',
            'status' => 'required|integer|exists:statuses,id'
        ];

        if ($id)
        {
            $rules['id'] = 'required|integer|exists:vehicles,id';
            $rules['picture'] = 'mimes:jpg,jpeg,png,bmp';
        }
        else
        {
            $rules['picture'] = 'required|mimes:jpg,jpeg,png,bmp';
        }

        return $rules;
    }
}
