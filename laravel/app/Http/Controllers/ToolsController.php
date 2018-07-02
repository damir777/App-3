<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CustomValidator\Validator as CustomValidator;
use App\Tool;
use App\Repositories\ToolRepository;
use App\Repositories\ManufacturerRepository;
use App\Repositories\GeneralRepository;

class ToolsController extends Controller
{
    //set repo variable
    protected $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new ToolRepository;
    }

    //get tools
    public function getTools()
    {
        //call getTools method from ToolRepository to get tools
        $tools = $this->repo->getTools();

        //if response status = 0 return error message
        if ($tools['status'] == 0)
        {
            return view('errors.500');
        }

        return view('tools.list', ['tools' => $tools['data']]);
    }

    //add tool
    public function addTool()
    {
        //call getManufacturersSelect method from ManufacturerRepository to get manufacturers - select
        $this->repo = new ManufacturerRepository;
        $manufacturers = $this->repo->getManufacturersSelect();

        //call getGeneralTypesSelect method from GeneralRepository to get tool types - select
        $this->repo = new GeneralRepository;
        $types = $this->repo->getGeneralTypesSelect(2);

        //call getStatusesSelect method from GeneralRepository to get statuses - select
        $statuses = $this->repo->getStatusesSelect();

        //if response status = 0 return error message
        if ($manufacturers['status'] == 0 || $types['status'] == 0 || $statuses['status'] == 0)
        {
            return view('errors.500');
        }

        return view('tools.addTool', ['manufacturers' => $manufacturers['data'], 'types' => $types['data'],
            'statuses' => $statuses['data']]);
    }

    //insert tool - ajax
    public function insertTool(Request $request)
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
        $internal_code = $request->internal_code;
        $purchase_date = $request->purchase_date;
        $sale_date = $request->sale_date;
        $status = $request->status;
        $notes = $request->notes;

        //validate form inputs
        $validator = Validator::make($request->all(), Tool::validateToolForm());

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 2]);
        }
        /*
        //validate tool code
        $validator = CustomValidator::validate('tool', ['code']);

        //if form input is not correct return error message
        if (!$validator['status'])
        {
            return response()->json(['status' => 3, 'error' => $validator['error']]);
        }*/

        //call insertTool method from ToolRepository to insert tool
        $response = $this->repo->insertTool($code, $manufacturer, $name, $model, $picture, $manufacture_year, $serial_number,
            $mass, $type, $internal_code, $purchase_date, $sale_date, $status, $notes);

        return response()->json($response);
    }

    //edit tool
    public function editTool($id)
    {
        //call getManufacturersSelect method from ManufacturerRepository to get manufacturers - select
        $this->repo = new ManufacturerRepository;
        $manufacturers = $this->repo->getManufacturersSelect();

        //call getGeneralTypesSelect method from GeneralRepository to get tool types - select
        $this->repo = new GeneralRepository;
        $types = $this->repo->getGeneralTypesSelect(2);

        //call getStatusesSelect method from GeneralRepository to get statuses - select
        $statuses = $this->repo->getStatusesSelect();

        //call getToolDetails method from ToolRepository to get tool details
        $this->repo = new ToolRepository;
        $tool = $this->repo->getToolDetails($id);

        //if response status = 0 return error message
        if ($manufacturers['status'] == 0 || $types['status'] == 0 || $statuses['status'] == 0 || $tool['status'] == 0)
        {
            return redirect()->route('GetTools')->with('error_message', trans('errors.error'));
        }

        return view('tools.editTool', ['manufacturers' => $manufacturers['data'], 'types' => $types['data'],
            'statuses' => $statuses['data'], 'tool' => $tool['data']]);
    }

    //update tool - ajax
    public function updateTool(Request $request)
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
        $internal_code = $request->internal_code;
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
        $validator = Validator::make($request->all(), Tool::validateToolForm($id));

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 2]);
        }
        /*
        //validate tool code
        $validator = CustomValidator::validate('tool', ['code'], $id);

        //if form input is not correct return error message
        if (!$validator['status'])
        {
            return response()->json(['status' => 3, 'error' => $validator['error']]);
        }*/

        //call updateTool method from ToolRepository to update tool
        $response = $this->repo->updateTool($id, $code, $manufacturer, $name, $model, $picture, $manufacture_year, $serial_number,
            $mass, $type, $internal_code, $purchase_date, $sale_date, $status, $notes, $new_picture);

        return response()->json($response);
    }
}
