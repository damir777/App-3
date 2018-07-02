<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CustomValidator\Validator as CustomValidator;
use App\DWA;
use App\DWAActivity;
use App\DWAFuel;
use App\DWAFluid;
use App\DWAFilter;
use App\DWANote;
use App\Repositories\DWARepository;

class DWAController extends Controller
{
    //set repo variable
    protected $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new DWARepository();
    }

    //get daily work activities
    public function getDWA(Request $request)
    {
        //get search parameters
        $site = $request->site;
        $machine = $request->machine;

        //call getDWA method from DWARepository to get daily work activities
        $activities = $this->repo->getDWA($site, $machine);

        //if response status = 0 return error message
        if ($activities['status'] == 0)
        {
            return view('errors.500');
        }

        return view('dwa.list', ['activities' => $activities['activities'], 'work_type' => $activities['work_type'],
            'sites' => $activities['sites'], 'machines' => $activities['machines'], 'site' => $site, 'machine' => $machine]);
    }

    //new entry
    public function newEntry()
    {
        return view('dwa.entry');
    }

    //get daily work activity details
    public function getDWADetails($id)
    {
        //call getDWADetails method from DWARepository to get dwa details
        $dwa = $this->repo->getDWADetails($id);

        //call getActivities method from DWARepository to get activities
        $activities = $this->repo->getActivities($id);

        //call getFuel method from DWARepository to get fuel
        $fuel = $this->repo->getFuel($id);

        //call getFluids method from DWARepository to get fluids
        $fluids = $this->repo->getFluids($id);

        //call getFilters method from DWARepository to get filters
        $filters = $this->repo->getFilters($id);

        //call getNotes method from DWARepository to get notes
        $notes = $this->repo->getNotes($id);

        //if response status = 0 return error message
        if ($dwa['status'] == 0 || $activities['status'] == 0 || $fuel['status'] == 0 || $fluids['status'] == 0 || $filters['status'] == 0 ||
            $notes['status'] == 0)
        {
            return redirect()->route('GetDWA')->with('error_message', trans('errors.error'));
        }

        return view('dwa.view', ['dwa' => $dwa['dwa'], 'head_of_site_confirmation' => $dwa['confirmation']['head_of_site_confirmation'],
            'manager_confirmation' => $dwa['confirmation']['manager_confirmation'], 'can_confirm' => $dwa['confirmation']['can_confirm'],
            'activities' => $activities['activities'], 'hours_sum' => $activities['hours_sum'],
            'working_hours_sum' => $activities['working_hours_sum'], 'fuel' => $fuel['fuel'], 'fluids' => $fluids['fluids'],
            'filters' => $filters['filters'], 'notes' => $notes['notes']]);
    }

    //edit daily work activity
    public function editDWA($id)
    {
        $dwa = DWA::find($id);

        //if dwa doesn't exist return error message
        if (!$dwa)
        {
            return redirect()->route('GetDWA')->with('error_message', trans('errors.error'));
        }

        //call isDWAConfirmed method from DWARepository to check if dwa is confirmed
        $confirmed_dwa = $this->repo->isDWAConfirmed(null, $id);

        if ($confirmed_dwa)
        {
            //if dwa is confirmed return warning message
            return redirect()->route('GetDWA')->with('warning_message', trans('main.dwa_confirmed_info'));
        }

        return view('dwa.edit', ['machine_id' => $dwa->machine_id, 'dwa_id' => $id]);
    }

    //confirm daily work activity
    public function confirmDWA($id)
    {
        //call confirmDWA method from DWARepository to confirm daily work activity
        $response = $this->repo->confirmDWA($id);

        //if response status = 0 return error message
        if ($response['status'] == 0)
        {
            return redirect()->route('ViewDWA', $id)->with('error_message', trans('errors.error'));
        }
        //if response status = 2 return info message
        elseif ($response['status'] == 2)
        {
            return redirect()->route('ViewDWA', $id)->with('info_message', trans('errors.dwa_confirmation_not_allowed'));
        }

        return redirect()->route('ViewDWA', $id)->with('success_message', trans('main.dwa_confirmation'));
    }

    //get initial dwa data
    public function getInitialData(Request $request)
    {
        $dwa_id = $request->dwa_id;

        //call getInitialData method from DWARepository to get initial dwa data
        $data = $this->repo->getInitialData($dwa_id);

        return response()->json($data);
    }

    //check machine daily work activities
    public function checkMachineDWA(Request $request)
    {
        $site_id = $request->site_id;
        $machine_id = $request->machine_id;

        //call checkMachineDWA method from DWARepository to check if dwa is already created
        $data = $this->repo->checkMachineDWA($site_id, $machine_id);

        return response()->json($data);
    }

    //create daily work activity
    public function createDWA(Request $request)
    {
        $site_id = $request->site_id;
        $employee_id = $request->employee_id;
        $machine_id = $request->machine_id;
        $machine_checked = $request->machine_checked;
        $damage = $request->damage;
        $damage_note = $request->damage_note;

        //validate form inputs
        $validator = Validator::make($request->all(), DWA::$createDWARules);

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 0, 'error' => trans('errors.validation_error')]);
        }

        //validate current site resources
        $validator = CustomValidator::currentSiteResourcesValidation('F', $site_id, [[1, $machine_id], [5, $employee_id]], null);

        //if form input is not correct return warning message
        if (!$validator['status'])
        {
            return response()->json(['status' => 3, 'warning' => $validator['warning']]);
        }

        //call checkMachineDWA method from DWARepository to check if dwa is already created
        $check_dwa = $this->repo->checkMachineDWA($site_id, $machine_id);

        //if machine dwa exists return info message
        if ($check_dwa['status'] == 1)
        {
            return response()->json(['status' => 2, 'info' => trans('main.dwa_exists_info'), 'dwa_id' => $check_dwa['dwa_id']]);
        }

        //call createDWA method from DWARepository to create daily work activity
        $data = $this->repo->createDWA($site_id, $employee_id, $machine_id, $machine_checked, $damage, $damage_note);

        return response()->json($data);
    }

    //save activity
    public function saveActivity(Request $request)
    {
        $is_edit = $request->is_edit;
        $dwa_id = $request->dwa_id;
        $site_id = $request->site_id;
        $employee_id = $request->employee_id;
        $machine_id = $request->machine_id;
        $start_time = $request->start_time;
        $end_time = $request->end_time;
        $tool_id = $request->tool_id;
        $activity = $request->activity;
        $start_working_hours = $request->start_working_hours;
        $end_working_hours = $request->end_working_hours;

        //validate form inputs
        $validator = Validator::make($request->all(), DWAActivity::saveActivityRules());

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 0, 'error' => trans('errors.validation_error')]);
        }

        //validate form inputs
        $end_time_validator = Validator::make($request->all(), DWAActivity::$end_time);

        //if end time is smaller or equal start time return warning message
        if (!$end_time_validator->passes())
        {
            return response()->json(['status' => 3, 'warning' => trans('errors.activity_end_time')]);
        }

        //validate form inputs
        $end_working_hours_validator = Validator::make($request->all(), DWAActivity::$end_working_hours);

        //if end work hours is equal or smaller than start work hours return warning message
        if (!$end_working_hours_validator->passes())
        {
            return response()->json(['status' => 3, 'warning' => trans('errors.end_working_hours')]);
        }

        //set current site validation array
        $current_site_validation_array = [[1, $machine_id], [5, $employee_id]];

        //if tool id is not '0' add tool to current site validation array
        if ($tool_id != 0)
        {
            $current_site_validation_array[] = [2, $tool_id];
        }

        //validate current site resources
        $validator = CustomValidator::currentSiteResourcesValidation($is_edit, $site_id, $current_site_validation_array, $dwa_id);

        //if form input is not correct return warning message
        if (!$validator['status'])
        {
            return response()->json(['status' => 3, 'warning' => $validator['warning']]);
        }

        if ($is_edit == 'F')
        {
            //call checkMachineDWA method from DWARepository to check if dwa is already created
            $check_dwa = $this->repo->checkMachineDWA($site_id, $machine_id);

            //if response = 0 return error message
            if ($check_dwa['status'] == 0)
            {
                return response()->json(['status' => 0, 'error' => trans('errors.error')]);
            }
            //if machine dwa doesn't exist return info message
            elseif ($check_dwa['status'] == 2)
            {
                return response()->json(['status' => 2, 'info' => $check_dwa['info']]);
            }
        }

        //call saveActivity method from DWARepository to save activity
        $data = $this->repo->saveActivity($is_edit, $dwa_id, $site_id, $employee_id, $machine_id, $start_time, $end_time, $tool_id, $activity,
            $start_working_hours, $end_working_hours);

        return response()->json($data);
    }

    //save fuel
    public function saveFuel(Request $request)
    {
        $is_edit = $request->is_edit;
        $dwa_id = $request->dwa_id;
        $site_id = $request->site_id;
        $employee_id = $request->employee_id;
        $machine_id = $request->machine_id;
        $quantity = $request->quantity;
        $invoice_number = $request->invoice_number;

        //validate form inputs
        $validator = Validator::make($request->all(), DWAFuel::$saveFuelRules);

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 0, 'error' => trans('errors.validation_error')]);
        }

        //validate current site resources
        $validator = CustomValidator::currentSiteResourcesValidation($is_edit, $site_id, [[1, $machine_id], [5, $employee_id]], $dwa_id);

        //if form input is not correct return warning message
        if (!$validator['status'])
        {
            return response()->json(['status' => 3, 'warning' => $validator['warning']]);
        }

        if ($is_edit == 'F')
        {
            //call checkMachineDWA method from DWARepository to check if dwa is already created
            $check_dwa = $this->repo->checkMachineDWA($site_id, $machine_id);

            //if response = 0 return error message
            if ($check_dwa['status'] == 0)
            {
                return response()->json(['status' => 0, 'error' => trans('errors.error')]);
            }
            //if machine dwa doesn't exist return info message
            elseif ($check_dwa['status'] == 2)
            {
                return response()->json(['status' => 2, 'info' => $check_dwa['info']]);
            }
        }

        //call saveFuel method from DWARepository to save fuel
        $data = $this->repo->saveFuel($is_edit, $dwa_id, $site_id, $employee_id, $machine_id, $quantity, $invoice_number);

        return response()->json($data);
    }

    //save fluid
    public function saveFluid(Request $request)
    {
        $is_edit = $request->is_edit;
        $dwa_id = $request->dwa_id;
        $site_id = $request->site_id;
        $employee_id = $request->employee_id;
        $machine_id = $request->machine_id;
        $component = $request->component;
        $quantity = $request->quantity;

        //validate form inputs
        $validator = Validator::make($request->all(), DWAFluid::validateSaveFluid());

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 0, 'error' => trans('errors.validation_error')]);
        }

        //validate current site resources
        $validator = CustomValidator::currentSiteResourcesValidation($is_edit, $site_id, [[1, $machine_id], [5, $employee_id]], $dwa_id);

        //if form input is not correct return warning message
        if (!$validator['status'])
        {
            return response()->json(['status' => 3, 'warning' => $validator['warning']]);
        }

        if ($is_edit == 'F')
        {
            //call checkMachineDWA method from DWARepository to check if dwa is already created
            $check_dwa = $this->repo->checkMachineDWA($site_id, $machine_id);

            //if response = 0 return error message
            if ($check_dwa['status'] == 0)
            {
                return response()->json(['status' => 0, 'error' => trans('errors.error')]);
            }
            //if machine dwa doesn't exist return info message
            elseif ($check_dwa['status'] == 2)
            {
                return response()->json(['status' => 2, 'info' => $check_dwa['info']]);
            }
        }

        //call saveFluid method from DWARepository to save fluid
        $data = $this->repo->saveFluid($is_edit, $dwa_id, $site_id, $employee_id, $machine_id, $component, $quantity);

        return response()->json($data);
    }

    //save filter
    public function saveFilter(Request $request)
    {
        $is_edit = $request->is_edit;
        $dwa_id = $request->dwa_id;
        $site_id = $request->site_id;
        $employee_id = $request->employee_id;
        $machine_id = $request->machine_id;
        $component = $request->component;
        $quantity = $request->quantity;

        //validate form inputs
        $validator = Validator::make($request->all(), DWAFilter::validateSaveFilter());

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 0, 'error' => trans('errors.validation_error')]);
        }

        //validate current site resources
        $validator = CustomValidator::currentSiteResourcesValidation($is_edit, $site_id, [[1, $machine_id], [5, $employee_id]], $dwa_id);

        //if form input is not correct return warning message
        if (!$validator['status'])
        {
            return response()->json(['status' => 3, 'warning' => $validator['warning']]);
        }

        if ($is_edit == 'F')
        {
            //call checkMachineDWA method from DWARepository to check if dwa is already created
            $check_dwa = $this->repo->checkMachineDWA($site_id, $machine_id);

            //if response = 0 return error message
            if ($check_dwa['status'] == 0)
            {
                return response()->json(['status' => 0, 'error' => trans('errors.error')]);
            }
            //if machine dwa doesn't exist return info message
            elseif ($check_dwa['status'] == 2)
            {
                return response()->json(['status' => 2, 'info' => $check_dwa['info']]);
            }
        }

        //call saveFilter method from DWARepository to save filter
        $data = $this->repo->saveFilter($is_edit, $dwa_id, $site_id, $employee_id, $machine_id, $component, $quantity);

        return response()->json($data);
    }

    //save note
    public function saveNote(Request $request)
    {
        $is_edit = $request->is_edit;
        $dwa_id = $request->dwa_id;
        $site_id = $request->site_id;
        $employee_id = $request->employee_id;
        $machine_id = $request->machine_id;
        $note = $request->note;
        $photo = $request->photo;

        //validate form inputs
        $validator = Validator::make($request->all(), DWANote::$saveNoteRules);

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 0, 'error' => trans('errors.validation_error')]);
        }

        //validate current site resources
        $validator = CustomValidator::currentSiteResourcesValidation($is_edit, $site_id, [[1, $machine_id], [5, $employee_id]], $dwa_id);

        //if form input is not correct return warning message
        if (!$validator['status'])
        {
            return response()->json(['status' => 3, 'warning' => $validator['warning']]);
        }

        if ($is_edit == 'F')
        {
            //call checkMachineDWA method from DWARepository to check if dwa is already created
            $check_dwa = $this->repo->checkMachineDWA($site_id, $machine_id);

            //if response = 0 return error message
            if ($check_dwa['status'] == 0)
            {
                return response()->json(['status' => 0, 'error' => trans('errors.error')]);
            }
            //if machine dwa doesn't exist return info message
            elseif ($check_dwa['status'] == 2)
            {
                return response()->json(['status' => 2, 'info' => $check_dwa['info']]);
            }
        }

        //call saveNote method from DWARepository to save note
        $data = $this->repo->saveNote($is_edit, $dwa_id, $site_id, $employee_id, $machine_id, $note, $photo);

        return response()->json($data);
    }

    //get activities
    public function getActivities(Request $request)
    {
        $dwa_id = $request->dwa_id;

        //call getActivities method from DWARepository to get activities
        $data = $this->repo->getActivities($dwa_id);

        return response()->json($data);
    }

    //get notes
    public function getNotes(Request $request)
    {
        $dwa_id = $request->dwa_id;

        //call getNotes method from DWARepository to get notes
        $data = $this->repo->getNotes($dwa_id);

        return response()->json($data);
    }

    //get fuel
    public function getFuel(Request $request)
    {
        $dwa_id = $request->dwa_id;

        //call getFuel method from DWARepository to get fuel
        $data = $this->repo->getFuel($dwa_id);

        return response()->json($data);
    }

    //get fluids
    public function getFluids(Request $request)
    {
        $dwa_id = $request->dwa_id;

        //call getFluids method from DWARepository to get fluids
        $data = $this->repo->getFluids($dwa_id);

        return response()->json($data);
    }

    //get filters
    public function getFilters(Request $request)
    {
        $dwa_id = $request->dwa_id;

        //call getFilters method from DWARepository to get filters
        $data = $this->repo->getFilters($dwa_id);

        return response()->json($data);
    }

    //delete activity
    public function deleteActivity(Request $request)
    {
        $dwa_id = $request->dwa_id;
        $activity_id = $request->activity_id;

        //call deleteActivity method from DWARepository to delete activity
        $response = $this->repo->deleteActivity($dwa_id, $activity_id);

        return response()->json($response);
    }

    //delete fuel
    public function deleteFuel(Request $request)
    {
        $dwa_id = $request->dwa_id;
        $fuel_id = $request->fuel_id;

        //call deleteFuel method from DWARepository to delete fuel
        $response = $this->repo->deleteFuel($dwa_id, $fuel_id);

        return response()->json($response);
    }

    //delete fluid
    public function deleteFluid(Request $request)
    {
        $dwa_id = $request->dwa_id;
        $fluid_id = $request->fluid_id;

        //call deleteFluid method from DWARepository to delete fluid
        $response = $this->repo->deleteFluid($dwa_id, $fluid_id);

        return response()->json($response);
    }

    //delete filter
    public function deleteFilter(Request $request)
    {
        $dwa_id = $request->dwa_id;
        $filter_id = $request->filter_id;

        //call deleteFilter method from DWARepository to delete filter
        $response = $this->repo->deleteFilter($dwa_id, $filter_id);

        return response()->json($response);
    }
}
