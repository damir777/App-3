<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\GeneralRepository;
use App\GeneralType;

class GeneralTypesController extends Controller
{
    //set repo variable
    protected $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new GeneralRepository;
    }

    //insert general type - ajax
    public function insertGeneralType(Request $request)
    {
        $type = $request->type;
        $name = $request->name;

        //validate form inputs
        $validator = Validator::make($request->all(), GeneralType::$insert_rules);

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 2]);
        }

        //call insertGeneralType method from GeneralTypeRepository to insert general type
        $response = $this->repo->insertGeneralType($type, $name);

        return response()->json($response);
    }

    //get general types - select - ajax
    public function getGeneralTypesSelect(Request $request)
    {
        $type = $request->type;

        //call getGeneralTypesSelect method from GeneralTypeRepository to get general types - select
        $manufacturers = $this->repo->getGeneralTypesSelect($type);

        return response()->json($manufacturers);
    }
}
