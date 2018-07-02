<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\ManufacturerRepository;
use App\Manufacturer;

class ManufacturersController extends Controller
{
    //set repo variable
    protected $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new ManufacturerRepository;
    }

    //insert manufacturer - ajax
    public function insertManufacturer(Request $request)
    {
        $name = $request->name;

        //validate form inputs
        $validator = Validator::make($request->all(), Manufacturer::$insert_rules);

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 2]);
        }

        //call insertManufacturer method from ManufacturerRepository to insert manufacturer
        $response = $this->repo->insertManufacturer($name);

        return response()->json($response);
    }

    //get manufacturers - select - ajax
    public function getManufacturersSelect()
    {
        //call getManufacturersSelect method from ManufacturerRepository to get manufacturers - select
        $manufacturers = $this->repo->getManufacturersSelect();

        return response()->json($manufacturers);
    }
}
