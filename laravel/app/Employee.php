<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Employee extends Model
{
    public $timestamps = false;

    use SoftDeletes;

    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    public function workType()
    {
        return $this->belongsTo('App\GeneralType', 'work_type_id');
    }

    public function contractType()
    {
        return $this->belongsTo('App\GeneralType', 'contract_type_id');
    }

    //form validation - insert/update employee
    public static function validateEmployeeForm($id = false)
    {
        $rules = [
            'code' => 'required',
            'name' => 'required',
            'work_type' => ['required', Rule::exists('general_types', 'id')->where(function ($query) {
                $query->where('type', 5); })],
            'contract_type' => ['required', Rule::exists('general_types', 'id')->where(function ($query) {
                $query->where('type', 7); })],
            'sex' => 'required|in:M,Ž',
            'oib' => 'required|oib',
            'birth_date' => 'required|custom_date',
            'citizenship' => 'required|integer|exists:countries,id',
            'birth_city' => 'required',
            'country' => 'required|integer|exists:countries,id',
            'city_id' => 'required|integer|exists:cities,id',
            'city' => 'required_unless:country,1',
            'address' => 'required',
            'phone' => 'required',
            'contract_start_date' => 'required|custom_date',
            'contract_expire_date' => 'nullable|custom_date',
            'medical_certificate_expire_date' => 'nullable|custom_date',
            'contract_end_date' => 'nullable|custom_date',
            'status' => 'required|integer|exists:statuses,id',
            'user_role' => 'required|integer|exists:roles,id'
        ];

        if ($id)
        {
            $rules['id'] = ['required', 'integer', Rule::exists('employees', 'id')->where(function ($query) {
                $query->where('id', '!=', 1); })];
        }
        else
        {
            $rules['picture'] = 'required|mimes:jpg,jpeg,png,bmp';
        }

        return $rules;
    }
}
