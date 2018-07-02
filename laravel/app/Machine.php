<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Request as Request;

class Machine extends Model
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

    //form validation - insert/update machine
    public static function validateMachineForm($id = false)
    {
        //get form input
        $input = Request::all();

        $rules = [
            'code' => 'required|integer',
            'manufacturer' => 'required|integer|exists:manufacturers,id',
            'name' => 'required',
            'model' => 'required',
            'manufacture_year' => 'required|year',
            'serial_number' => 'required',
            'mass' => 'required|integer',
            'type' => ['required', 'integer', Rule::exists('general_types', 'id')->where(function ($query) { $query->where('type', 1); })],
            'pin' => 'required',
            'purchase_date' => 'required|custom_date',
            'sale_date' => 'nullable|custom_date',
            'start_working_hours' => 'required|integer',
            'end_working_hours' => 'nullable|integer',
            'certificate_end_date' => 'required|custom_date',
            'status' => 'required|integer|exists:statuses,id'
        ];

        if ($input['register_number'])
        {
            $rules['register_date'] = 'required|custom_date';
        }

        if ($id)
        {
            $rules['id'] = 'required|integer|exists:machines,id';
            $rules['picture'] = 'mimes:jpg,jpeg,png,bmp';
        }
        else
        {
            $rules['picture'] = 'required|mimes:jpg,jpeg,png,bmp';
        }

        return $rules;
    }
}
