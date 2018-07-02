<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\InvestorRepository;
use App\Investor;

class InvestorsController extends Controller
{
    //set repo variable
    protected $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new InvestorRepository;
    }

    //insert investor - ajax
    public function insertInvestor(Request $request)
    {
        $name = $request->name;
        $country = $request->country;
        $city_id = $request->city_id;
        $city = $request->city;
        $address = $request->address;

        //validate form inputs
        $validator = Validator::make($request->all(), Investor::$insert_rules);

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 2]);
        }

        //call insertInvestor method from InvestorRepository to insert investor
        $response = $this->repo->insertInvestor($name, $country, $city_id, $city, $address);

        return response()->json($response);
    }

    //get investors - select - ajax
    public function getInvestorsSelect()
    {
        //call getInvestorsSelect method from InvestorRepository to get investors - select
        $investors = $this->repo->getInvestorsSelect();

        return response()->json($investors);
    }
}
