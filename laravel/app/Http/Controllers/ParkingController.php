<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Parking;
use App\Repositories\ParkingRepository;
use App\Repositories\GeneralRepository;

class ParkingController extends Controller
{
    //set repo variable
    protected $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new ParkingRepository;
    }

    //get parking
    public function getParking()
    {
        //call getParking method from ParkingRepository to get parking
        $parking = $this->repo->getParking();

        //if response status = 0 return error message
        if ($parking['status'] == 0)
        {
            return view('errors.500');
        }

        return view('parking.list', ['parking' => $parking['data']]);
    }

    //add parking
    public function addParking()
    {
        //call getStatusesSelect method from GeneralRepository to get statuses - select
        $this->repo = new GeneralRepository;
        $statuses = $this->repo->getStatusesSelect();

        //if response status = 0 return error message
        if ($statuses['status'] == 0)
        {
            return view('errors.500');
        }

        return view('parking.addParking', ['statuses' => $statuses['data']]);
    }

    //insert parking - ajax
    public function insertParking(Request $request)
    {
        $name = $request->name;
        $address = $request->address;
        $status = $request->status;
        $notes = $request->notes;
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        //validate form inputs
        $validator = Validator::make($request->all(), Parking::validateParkingForm());

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 2]);
        }

        //call insertParking method from ParkingRepository to insert parking
        $response = $this->repo->insertParking($name, $address, $status, $notes, $latitude, $longitude);

        return response()->json($response);
    }

    //edit parking
    public function editParking($id)
    {
        //call getStatusesSelect method from GeneralRepository to get statuses - select
        $this->repo = new GeneralRepository;
        $statuses = $this->repo->getStatusesSelect();

        //call getParkingDetails method from ParkingRepository to get parking details
        $this->repo = new ParkingRepository;
        $parking = $this->repo->getParkingDetails($id);

        //if response status = 0 return error message
        if ($statuses['status'] == 0 || $parking['status'] == 0)
        {
            return redirect()->route('GetParking')->with('error_message', trans('errors.error'));
        }

        return view('parking.editParking', ['statuses' => $statuses['data'], 'parking' => $parking['data']]);
    }

    //update parking - ajax
    public function updateParking(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $address = $request->address;
        $status = $request->status;
        $notes = $request->notes;
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        //validate form inputs
        $validator = Validator::make($request->all(), Parking::validateParkingForm($id));

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 2]);
        }

        //call updateParking method from ParkingRepository to update parking
        $response = $this->repo->updateParking($id, $name, $address, $status, $notes, $latitude, $longitude);

        return response()->json($response);
    }
}
