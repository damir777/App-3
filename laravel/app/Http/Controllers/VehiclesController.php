<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CustomValidator\Validator as CustomValidator;
use App\Vehicle;
use App\Repositories\VehicleRepository;
use App\Repositories\ManufacturerRepository;
use App\Repositories\GeneralRepository;

class VehiclesController extends Controller
{
    //set repo variable
    protected $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new VehicleRepository;
    }

    //get vehicles
    public function getVehicles()
    {
        //call getVehicles method from VehicleRepository to get vehicles
        $vehicles = $this->repo->getVehicles();

        //if response status = 0 return error message
        if ($vehicles['status'] == 0)
        {
            return view('errors.500');
        }

        return view('vehicles.list', ['vehicles' => $vehicles['data']]);
    }

    //add vehicle
    public function addVehicle()
    {
        //call getManufacturersSelect method from ManufacturerRepository to get manufacturers - select
        $this->repo = new ManufacturerRepository;
        $manufacturers = $this->repo->getManufacturersSelect();

        //call getGeneralTypesSelect method from GeneralRepository to get vehicle types - select
        $this->repo = new GeneralRepository;
        $types = $this->repo->getGeneralTypesSelect(4);

        //call getGeneralTypesSelect method from GeneralRepository to get fuel types - select
        $fuel_types = $this->repo->getGeneralTypesSelect(6);

        //call getStatusesSelect method from GeneralRepository to get statuses - select
        $statuses = $this->repo->getStatusesSelect();

        //if response status = 0 return error message
        if ($manufacturers['status'] == 0 || $types['status'] == 0 || $fuel_types['status'] == 0 || $statuses['status'] == 0)
        {
            return view('errors.500');
        }

        return view('vehicles.addVehicle', ['manufacturers' => $manufacturers['data'], 'types' => $types['data'],
            'fuel_types' => $fuel_types['data'], 'statuses' => $statuses['data']]);
    }

    //insert vehicle - ajax
    public function insertVehicle(Request $request)
    {
        $code = $request->code;
        $manufacturer = $request->manufacturer;
        $name = $request->name;
        $model = $request->model;
        $picture = $request->picture;
        $manufacture_year = $request->manufacture_year;
        $mass = $request->mass;
        $type = $request->type;
        $seats_number = $request->seats_number;
        $chassis_number = $request->chassis_number;
        $fuel_type = $request->fuel_type;
        $purchase_date = $request->purchase_date;
        $sale_date = $request->sale_date;
        $start_mileage = $request->start_mileage;
        $end_working_hours = $request->end_working_hours;
        $register_number = $request->register_number;
        $register_date = $request->register_date;
        $status = $request->status;
        $notes = $request->notes;

        //validate form inputs
        $validator = Validator::make($request->all(), Vehicle::validateVehicleForm());

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 2]);
        }

        //validate vehicle code
        $validator = CustomValidator::validate('vehicle', ['code']);

        //if form input is not correct return error message
        if (!$validator['status'])
        {
            return response()->json(['status' => 3, 'error' => $validator['error']]);
        }

        //call insertVehicle method from VehicleRepository to insert vehicle
        $response = $this->repo->insertVehicle($code, $manufacturer, $name, $model, $picture, $manufacture_year, $mass, $type,
            $seats_number, $chassis_number, $fuel_type, $purchase_date, $sale_date, $start_mileage, $end_working_hours,
            $register_number, $register_date, $status, $notes);

        return response()->json($response);
    }

    //edit vehicle
    public function editVehicle($id)
    {
        //call getManufacturersSelect method from ManufacturerRepository to get manufacturers - select
        $this->repo = new ManufacturerRepository;
        $manufacturers = $this->repo->getManufacturersSelect();

        //call getGeneralTypesSelect method from GeneralRepository to get vehicle types - select
        $this->repo = new GeneralRepository;
        $types = $this->repo->getGeneralTypesSelect(4);

        //call getGeneralTypesSelect method from GeneralRepository to get fuel types - select
        $fuel_types = $this->repo->getGeneralTypesSelect(6);

        //call getStatusesSelect method from GeneralRepository to get statuses - select
        $statuses = $this->repo->getStatusesSelect();

        //call getVehicleDetails method from VehicleRepository to get vehicle details
        $this->repo = new VehicleRepository;
        $vehicle = $this->repo->getVehicleDetails($id);

        //if response status = 0 return error message
        if ($manufacturers['status'] == 0 || $types['status'] == 0 || $fuel_types['status'] == 0 || $statuses['status'] == 0 ||
            $vehicle['status'] == 0)
        {
            return redirect()->route('GetVehicles')->with('error_message', trans('errors.error'));
        }

        return view('vehicles.editVehicle', ['manufacturers' => $manufacturers['data'], 'types' => $types['data'],
            'fuel_types' => $fuel_types['data'], 'statuses' => $statuses['data'], 'vehicle' => $vehicle['data']]);
    }

    //update vehicle - ajax
    public function updateVehicle(Request $request)
    {
        $id = $request->id;
        $code = $request->code;
        $manufacturer = $request->manufacturer;
        $name = $request->name;
        $model = $request->model;
        $picture = $request->picture;
        $manufacture_year = $request->manufacture_year;
        $mass = $request->mass;
        $type = $request->type;
        $seats_number = $request->seats_number;
        $chassis_number = $request->chassis_number;
        $fuel_type = $request->fuel_type;
        $purchase_date = $request->purchase_date;
        $sale_date = $request->sale_date;
        $start_mileage = $request->start_mileage;
        $end_working_hours = $request->end_working_hours;
        $register_number = $request->register_number;
        $register_date = $request->register_date;
        $status = $request->status;
        $notes = $request->notes;

        //set default new picture variable
        $new_picture = 'F';

        //check if new picture exists
        if ($request->hasFile('picture'))
        {
            $new_picture = 'T';
        }

        //validate form inputs
        $validator = Validator::make($request->all(), Vehicle::validateVehicleForm($id));

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 2]);
        }

        //validate vehicle code
        $validator = CustomValidator::validate('vehicle', ['code'], $id);

        //if form input is not correct return error message
        if (!$validator['status'])
        {
            return response()->json(['status' => 3, 'error' => $validator['error']]);
        }

        //call updateVehicle method from VehicleRepository to update vehicle
        $response = $this->repo->updateVehicle($id, $code, $manufacturer, $name, $model, $picture, $manufacture_year, $mass, $type,
            $seats_number, $chassis_number, $fuel_type, $purchase_date, $sale_date, $start_mileage, $end_working_hours,
            $register_number, $register_date, $status, $notes, $new_picture);

        return response()->json($response);
    }
}
