<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CustomValidator\Validator as CustomValidator;
use App\Equipment;
use App\Repositories\EquipmentRepository;
use App\Repositories\ManufacturerRepository;
use App\Repositories\GeneralRepository;

class EquipmentController extends Controller
{
    //set repo variable
    protected $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new EquipmentRepository;
    }

    //get equipment
    public function getEquipment()
    {
        //call getEquipment method from EquipmentRepository to get equipment
        $equipment = $this->repo->getEquipment();

        //if response status = 0 return error message
        if ($equipment['status'] == 0)
        {
            return view('errors.500');
        }

        return view('equipment.list', ['equipment' => $equipment['data']]);
    }

    //add equipment
    public function addEquipment()
    {
        //call getManufacturersSelect method from ManufacturerRepository to get manufacturers - select
        $this->repo = new ManufacturerRepository;
        $manufacturers = $this->repo->getManufacturersSelect();

        //call getGeneralTypesSelect method from GeneralRepository to get equipment types - select
        $this->repo = new GeneralRepository;
        $types = $this->repo->getGeneralTypesSelect(3);

        //call getStatusesSelect method from GeneralRepository to get statuses - select
        $statuses = $this->repo->getStatusesSelect();

        //if response status = 0 return error message
        if ($manufacturers['status'] == 0 || $types['status'] == 0 || $statuses['status'] == 0)
        {
            return view('errors.500');
        }

        return view('equipment.addEquipment', ['manufacturers' => $manufacturers['data'], 'types' => $types['data'],
            'statuses' => $statuses['data']]);
    }

    //insert equipment - ajax
    public function insertEquipment(Request $request)
    {
        $code = $request->code;
        $manufacturer = $request->manufacturer;
        $name = $request->name;
        $model = $request->model;
        $picture = $request->picture;
        $manufacture_year = $request->manufacture_year;
        $serial_number = $request->serial_number;
        $mass = $request->mass;
        $type = $request->type;
        $purchase_date = $request->purchase_date;
        $sale_date = $request->sale_date;
        $status = $request->status;
        $notes = $request->notes;

        //validate form inputs
        $validator = Validator::make($request->all(), Equipment::validateEquipmentForm());

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 2]);
        }

        //validate equipment code
        $validator = CustomValidator::validate('equipment', ['code']);

        //if form input is not correct return error message
        if (!$validator['status'])
        {
            return response()->json(['status' => 3, 'error' => $validator['error']]);
        }

        //call insertEquipment method from EquipmentRepository to insert equipment
        $response = $this->repo->insertEquipment($code, $manufacturer, $name, $model, $picture, $manufacture_year, $serial_number,
            $mass, $type, $purchase_date, $sale_date, $status, $notes);

        return response()->json($response);
    }

    //edit equipment
    public function editEquipment($id)
    {
        //call getManufacturersSelect method from ManufacturerRepository to get manufacturers - select
        $this->repo = new ManufacturerRepository;
        $manufacturers = $this->repo->getManufacturersSelect();

        //call getGeneralTypesSelect method from GeneralRepository to get equipment types - select
        $this->repo = new GeneralRepository;
        $types = $this->repo->getGeneralTypesSelect(3);

        //call getStatusesSelect method from GeneralRepository to get statuses - select
        $statuses = $this->repo->getStatusesSelect();

        //call getEquipmentDetails method from EquipmentRepository to get equipment details
        $this->repo = new EquipmentRepository;
        $equipment = $this->repo->getEquipmentDetails($id);

        //if response status = 0 return error message
        if ($manufacturers['status'] == 0 || $types['status'] == 0 || $statuses['status'] == 0 || $equipment['status'] == 0)
        {
            return redirect()->route('GetEquipment')->with('error_message', trans('errors.error'));
        }

        return view('equipment.editEquipment', ['manufacturers' => $manufacturers['data'], 'types' => $types['data'],
            'statuses' => $statuses['data'], 'equipment' => $equipment['data']]);
    }

    //update equipment - ajax
    public function updateEquipment(Request $request)
    {
        $id = $request->id;
        $code = $request->code;
        $manufacturer = $request->manufacturer;
        $name = $request->name;
        $model = $request->model;
        $picture = $request->picture;
        $manufacture_year = $request->manufacture_year;
        $serial_number = $request->serial_number;
        $mass = $request->mass;
        $type = $request->type;
        $purchase_date = $request->purchase_date;
        $sale_date = $request->sale_date;
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
        $validator = Validator::make($request->all(), Equipment::validateEquipmentForm($id));

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 2]);
        }

        //validate equipment code
        $validator = CustomValidator::validate('equipment', ['code'], $id);

        //if form input is not correct return error message
        if (!$validator['status'])
        {
            return response()->json(['status' => 3, 'error' => $validator['error']]);
        }

        //call updateEquipment method from EquipmentRepository to update equipment
        $response = $this->repo->updateEquipment($id, $code, $manufacturer, $name, $model, $picture, $manufacture_year,
            $serial_number, $mass, $type, $purchase_date, $sale_date, $status, $notes, $new_picture);

        return response()->json($response);
    }
}
