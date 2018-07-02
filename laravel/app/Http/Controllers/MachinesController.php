<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CustomValidator\Validator as CustomValidator;
use App\Machine;
use App\Repositories\MachineRepository;
use App\Repositories\ManufacturerRepository;
use App\Repositories\GeneralRepository;

class MachinesController extends Controller
{
    //set repo variable
    protected $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new MachineRepository;
    }

    //get machines
    public function getMachines()
    {
        //call getMachines method from MachineRepository to get machines
        $machines = $this->repo->getMachines();

        //if response status = 0 return error message
        if ($machines['status'] == 0)
        {
            return view('errors.500');
        }

        return view('machines.list', ['machines' => $machines['data']]);
    }

    //add machine
    public function addMachine()
    {
        //call getManufacturersSelect method from ManufacturerRepository to get manufacturers - select
        $this->repo = new ManufacturerRepository;
        $manufacturers = $this->repo->getManufacturersSelect();

        //call getGeneralTypesSelect method from GeneralRepository to get machine types - select
        $this->repo = new GeneralRepository;
        $types = $this->repo->getGeneralTypesSelect(1);

        //call getStatusesSelect method from GeneralRepository to get statuses - select
        $statuses = $this->repo->getStatusesSelect();

        //if response status = 0 return error message
        if ($manufacturers['status'] == 0 || $types['status'] == 0 || $statuses['status'] == 0)
        {
            return view('errors.500');
        }

        return view('machines.addMachine', ['manufacturers' => $manufacturers['data'], 'types' => $types['data'],
            'statuses' => $statuses['data']]);
    }

    //insert machine - ajax
    public function insertMachine(Request $request)
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
        $pin = $request->pin;
        $purchase_date = $request->purchase_date;
        $sale_date = $request->sale_date;
        $start_working_hours = $request->start_working_hours;
        $end_working_hours = $request->end_working_hours;
        $register_number = $request->register_number;
        $register_date = $request->register_date;
        $certificate_end_date = $request->certificate_end_date;
        $status = $request->status;
        $notes = $request->notes;

        //validate form inputs
        $validator = Validator::make($request->all(), Machine::validateMachineForm());

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 2]);
        }

        //validate machine code
        $validator = CustomValidator::validate('machine', ['code']);

        //if form input is not correct return error message
        if (!$validator['status'])
        {
            return response()->json(['status' => 3, 'error' => $validator['error']]);
        }

        //call insertMachine method from MachineRepository to insert machine
        $response = $this->repo->insertMachine($code, $manufacturer, $name, $model, $picture, $manufacture_year, $serial_number,
            $mass, $type, $pin, $purchase_date, $sale_date, $start_working_hours, $end_working_hours, $register_number, $register_date,
            $certificate_end_date, $status, $notes);

        return response()->json($response);
    }

    //edit machine
    public function editMachine($id)
    {
        //call getManufacturersSelect method from ManufacturerRepository to get manufacturers - select
        $this->repo = new ManufacturerRepository;
        $manufacturers = $this->repo->getManufacturersSelect();

        //call getGeneralTypesSelect method from GeneralRepository to get machine types - select
        $this->repo = new GeneralRepository;
        $types = $this->repo->getGeneralTypesSelect(1);

        //call getStatusesSelect method from GeneralRepository to get statuses - select
        $statuses = $this->repo->getStatusesSelect();

        //call getMachineDetails method from MachineRepository to get machine details
        $this->repo = new MachineRepository;
        $machine = $this->repo->getMachineDetails($id);

        //if response status = 0 return error message
        if ($manufacturers['status'] == 0 || $types['status'] == 0 || $statuses['status'] == 0 || $machine['status'] == 0)
        {
            return redirect()->route('GetMachines')->with('error_message', trans('errors.error'));
        }

        return view('machines.editMachine', ['manufacturers' => $manufacturers['data'], 'types' => $types['data'],
            'statuses' => $statuses['data'], 'machine' => $machine['data']]);
    }

    //update machine - ajax
    public function updateMachine(Request $request)
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
        $pin = $request->pin;
        $purchase_date = $request->purchase_date;
        $sale_date = $request->sale_date;
        $start_working_hours = $request->start_working_hours;
        $end_working_hours = $request->end_working_hours;
        $register_number = $request->register_number;
        $register_date = $request->register_date;
        $certificate_end_date = $request->certificate_end_date;
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
        $validator = Validator::make($request->all(), Machine::validateMachineForm($id));

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 2]);
        }

        //validate machine code
        $validator = CustomValidator::validate('machine', ['code'], $id);

        //if form input is not correct return error message
        if (!$validator['status'])
        {
            return response()->json(['status' => 3, 'error' => $validator['error']]);
        }

        //call updateMachine method from MachineRepository to update machine
        $response = $this->repo->updateMachine($id, $code, $manufacturer, $name, $model, $picture, $manufacture_year, $serial_number,
            $mass, $type, $pin, $purchase_date, $sale_date, $start_working_hours, $end_working_hours, $register_number, $register_date,
            $certificate_end_date, $status, $notes, $new_picture);

        return response()->json($response);
    }
}
