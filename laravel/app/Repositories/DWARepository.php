<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

use App\Machine;
use App\DWA;
use App\DWAActivity;
use App\DWAFuel;
use App\DWAFluid;
use App\DWAFilter;
use App\DWANote;

class DWARepository
{
    //get daily work activities
    public function getDWA($site, $machine)
    {
        try
        {
            //call getUserEmployeeId method from EmployeeRepository to get employee id
            $repo = new EmployeeRepository;
            $employee_id = $repo->getUserEmployeeId();

            //call getUserOrEmployeeWorkType method from EmployeeRepository to get user work type
            $repo = new EmployeeRepository;
            $work_type = $repo->getUserOrEmployeeWorkType(null, $employee_id);

            //call filterEmployeeSitesAndMachines method to filter employee sites and machines
            $filters = $this->filterEmployeeSitesAndMachines($employee_id, $work_type);

            //if response status = 0 return error message
            if ($filters['status'] == 0)
            {
                return ['status' => 0];
            }

            $activities = DWA::with('site', 'machine')
                ->select('id', 'site_id', 'machine_id', DB::raw('DATE_FORMAT(activity_date, "%d.%m.%Y.") AS date'), 'confirmation_manager_id',
                    'confirmation_head_of_site_id');

            if ($site)
            {
                $activities->where('site_id', '=', $site);
            }

            if ($machine)
            {
                $activities->where('machine_id', '=', $machine);
            }

            if ($work_type < 4)
            {
                $activities->whereIn('machine_id', $filters['machines']);
            }

            $activities = $activities->orderBy('activity_date', 'desc')->paginate(30);

            foreach ($activities as $activity)
            {
                //call isDWAConfirmed method to check if dwa is confirmed
                $confirmed_dwa = $this->isDWAConfirmed(null, $activity->id);

                if (!$confirmed_dwa)
                {
                    $activity->show_edit = 'T';
                }
                else
                {
                    $activity->show_edit = 'F';
                }
            }

            return ['status' => 1, 'activities' => $activities, 'work_type' => $work_type, 'sites' => $filters['filter_sites'],
                'machines' => $filters['filter_machines']];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get initial dwa data
    public function getInitialData($dwa_id)
    {
        try
        {
            //call getAuthenticatedUser method from UserRepository to get authenticated user
            $repo = new UserRepository;
            $user = $repo->getAuthenticatedUser();

            //set default site id
            $site_id = 0;

            //set default additional sites array
            $additional_sites = [];

            //set default employee id
            $employee_id = 0;

            //set employees array
            $employees_array = [];

            //set activity_types array
            $activity_types_array = [];

            //set tools array
            $tools_array = [];

            //set fluid components array
            $fluid_components_array = [];

            //set filter components array
            $filter_components_array = [];

            //set dwa site id
            $dwa_site_id = null;

            //set dwa machine id
            $dwa_machine_id = null;

            //set dwa date variable
            $dwa_date = null;

            //if dwa id parameter exists get dwa site id, machine id and date
            if ($dwa_id)
            {
                $dwa = DWA::find($dwa_id);
                $dwa_site_id = $dwa->site_id;
                $dwa_machine_id = $dwa->machine_id;
                $dwa_date = $dwa->activity_date;

                //set site id
                $site_id = $dwa_site_id;
            }

            //if user has 'Management' role return warning message
            if ($user->hasRole('Management'))
            {
                return ['status' => 2, 'warning' => trans('errors.dwa_access_denied')];
            }

            //call getUserOrEmployeeWorkType method from EmployeeRepository to get user work type
            $repo = new EmployeeRepository;
            $work_type = $repo->getUserOrEmployeeWorkType($user->id);

            if (!$user->hasRole('HeadOfSite') && !$user->hasRole('Manager'))
            {
                //call getUserEmployeeId method from EmployeeRepository to get employee id
                $repo = new EmployeeRepository;
                $employee_id = $repo->getUserEmployeeId();
            }

            if ($user->hasRole('Mechanic') || $work_type == 3)
            {
                /*
                |--------------------------------------------------------------------------
                | Get all machines
                |--------------------------------------------------------------------------
                */

                //call getMachinesSelect method from MachineRepository to get machines - select
                $repo = new MachineRepository;
                $machines = $repo->getMachinesSelect();

                $machines_array = $machines['data'];
            }
            else
            {
                if (!$dwa_site_id)
                {
                    //call getCurrentUserSite method from ManipulationRepository to get current user site and additional sites
                    $repo = new ManipulationRepository;
                    $current_site = $repo->getCurrentUserSite($user);

                    //if response status = 2 return info message
                    if ($current_site['status'] == 2)
                    {
                        return ['status' => 2, 'warning' => trans('errors.dwa_current_user_site_not_assigned')];
                    }

                    //set site id and additional sites
                    $site_id = $current_site['site_id'];

                    //if employee has 'HeadOfSite' role check additional sites
                    if ($user->hasRole('HeadOfSite'))
                    {
                        //if employee has additional sites get main site and additional sites resources
                        if (count($current_site['additional_sites']) > 0)
                        {
                            //call getHeadOfSiteSitesAndResources method from ManipulationRepository get head of site sites and resources
                            $repo = new ManipulationRepository;
                            $additional_sites = $repo->getHeadOfSiteSitesAndResources($site_id, $current_site['additional_sites']);
                        }
                    }
                }
                else
                {
                    //call getCurrentUserHistorySite method from ManipulationRepository to get current user history site
                    $repo = new ManipulationRepository;
                    $current_site = $repo->getCurrentUserHistorySite($user, $site_id, $dwa_date);

                    if ($site_id != $current_site)
                    {
                        //if dwa site id is not equal head of site or manager site id return warning message
                        return ['status' => 2, 'warning' => trans('errors.dwa_edit_not_allowed')];
                    }
                }

                if ($user->hasRole('HeadOfSite') || $user->hasRole('Manager'))
                {
                    if (!$dwa_site_id)
                    {
                        //if employee doesn't have additional sites check for assigned employees
                        if (count($additional_sites) == 0)
                        {
                            //call getCurrentSiteDWAEmployees method from ManipulationRepository to get employees who can use daily work
                            //activity
                            $repo = new ManipulationRepository;
                            $employees = $repo->getCurrentSiteDWAEmployees($site_id);

                            if (count($employees) == 0)
                            {
                                //if there's no employees assigned to current site return info message
                                return ['status' => 2, 'warning' => trans('errors.dwa_current_site_employees_not_assigned')];
                            }

                            $employees_array = $employees;
                        }
                    }
                    else
                    {
                        //call getCurrentSiteDWAEmployees method from ManipulationRepository to get employees who can use daily work activity
                        $repo = new ManipulationRepository;
                        $employees = $repo->getCurrentSiteDWAEmployees($site_id, $dwa_date);

                        if (count($employees) == 0)
                        {
                            //if there's no employees assigned to current site return info message
                            return ['status' => 2, 'warning' => trans('errors.dwa_current_site_employees_not_assigned')];
                        }

                        $employees_array = $employees;
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Get site machines
                |--------------------------------------------------------------------------
                */

                //call getCurrentSiteResources method from ManipulationRepository to get current site resources
                $repo = new ManipulationRepository;
                $machines = $repo->getCurrentSiteResources($site_id, 1, $dwa_date);

                //if employee doesn't have additional sites check for assigned machines
                if (count($additional_sites) == 0)
                {
                    if (count($machines) == 1)
                    {
                        //if there's no machines assigned to current site return warning message
                        return ['status' => 2, 'warning' => trans('errors.dwa_current_site_machines_not_assigned')];
                    }
                }

                $machines_array = $machines;

                /*
                |--------------------------------------------------------------------------
                | Get activity types
                |--------------------------------------------------------------------------
                */

                //call getGeneralTypesSelect method from GeneralRepository to get activity types - select
                $repo = new GeneralRepository;
                $types = $repo->getGeneralTypesSelect(9);

                $activity_types_array = $types['data'];

                /*
                |--------------------------------------------------------------------------
                | Get site tools
                |--------------------------------------------------------------------------
                */

                //call getCurrentSiteResources method from ManipulationRepository to get current site tools
                $repo = new ManipulationRepository;
                $tools = $repo->getCurrentSiteResources($site_id, 2, $dwa_date);

                $tools_array = $tools;
            }

            /*
            |--------------------------------------------------------------------------
            | Get machine components - fluids
            |--------------------------------------------------------------------------
            */

            //call getMachineComponents method from MachineRepository to get machine components - fluids
            $repo = new MachineRepository;
            $fluid_components = $repo->getMachineComponents('fluid');

            foreach ($fluid_components as $component)
            {
                //add component to fluid components array
                $fluid_components_array[$component->id] = $component->name;
            }

            /*
            |--------------------------------------------------------------------------
            | Get machine components - filters
            |--------------------------------------------------------------------------
            */

            //call getMachineComponents method from MachineRepository to get machine components - filters
            $repo = new MachineRepository;
            $filter_components = $repo->getMachineComponents('filter');

            foreach ($filter_components as $component)
            {
                //add component to filter components array
                $filter_components_array[$component->id] = $component->name;
            }

            return ['status' => 1, 'site_id' => $site_id, 'additional_sites' => $additional_sites, 'employees' => $employees_array,
                'employee_id' => $employee_id, 'work_type' => $work_type, 'machines' => $machines_array, 'machine_id' => $dwa_machine_id,
                'activity_types' => $activity_types_array, 'tools' => $tools_array, 'fluid_components' => $fluid_components_array,
                'filter_components' => $filter_components_array, 'dwa_id' => $dwa_id, 'dwa_machine_id' => $dwa_machine_id];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //check machine daily work activity
    public function checkMachineDWA($site_id, $machine_id)
    {
        try
        {
            $machine_check = Machine::find($machine_id);

            if (!$machine_check)
            {
                //if machine doesn't exist return error message
                return ['status' => 0, 'error' => trans_choice('errors.invalid_resource', 1, ['resource' => trans('main.machine')])];
            }

            //call isDWAConfirmed method to check if dwa is confirmed
            $confirmed_dwa = $this->isDWAConfirmed($machine_id, null);

            if ($confirmed_dwa)
            {
                //if dwa is confirmed return warning message
                return ['status' => 3, 'info' => trans('main.dwa_confirmed_info')];
            }

            //get current date
            $current_date = date('Y-m-d');

            //call getAuthenticatedUser method from UserRepository to get authenticated user
            $repo = new UserRepository;
            $user = $repo->getAuthenticatedUser();

            //call getUserOrEmployeeWorkType method from EmployeeRepository to get user work type
            $repo = new EmployeeRepository;
            $work_type = $repo->getUserOrEmployeeWorkType($user->id);

            $dwa = DWA::select('id', 'site_id')->where('machine_id', '=', $machine_id)->where('activity_date', '=', $current_date);

            if ($user->hasRole('Admin') || $user->hasRole('HeadOfDepartment') || $user->hasRole('HeadOfSite') || $user->hasRole('Manager') ||
                ($user->hasRole('Employee') && $work_type == 1))
            {
                $dwa->where('site_id', '=', $site_id);
                $dwa = $dwa->first();

                if (!$dwa)
                {
                    //if dwa is not created return info message
                    return ['status' => 2, 'info' => trans('main.create_dwa_info')];
                }
            }
            else
            {
                $dwa = $dwa->first();

                if (!$dwa)
                {
                    //if dwa is not created return info message
                    return ['status' => 2, 'info' => trans('main.site_id_not_required_create_dwa_info')];
                }
            }

            return ['status' => 1, 'dwa_id' => $dwa->id, 'site_id' => $dwa->site_id];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //create daily work activity
    public function createDWA($site_id, $employee_id, $machine_id, $machine_checked, $damage, $damage_note)
    {
        try
        {
            //call getUserEmployeeId method from EmployeeRepository to get creator id
            $repo = new EmployeeRepository;
            $creator_id = $repo->getUserEmployeeId();

            //get current date
            $current_date = date('Y-m-d');

            //start transaction
            DB::beginTransaction();

            $dwa = new DWA;
            $dwa->site_id = $site_id;
            $dwa->machine_id = $machine_id;
            $dwa->creator_id = $creator_id;
            $dwa->activity_date = $current_date;
            $dwa->machine_checked = $machine_checked;
            $dwa->damage = $damage;
            $dwa->save();

            //if damage note is not empty insert new note
            if ($damage_note)
            {
                $note = new DWANote;
                $note->dwa_id = $dwa->id;
                $note->creator_id = $creator_id;
                $note->employee_id = $employee_id;
                $note->note = $damage_note;
                $note->save();
            }

            //commit transaction
            DB::commit();

            return ['status' => 1, 'success' => trans('main.dwa_insert'), 'dwa_id' => $dwa->id];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //get daily work activity id
    private function getDWAId($site_id, $machine_id)
    {
        //get current date
        $current_date = date('Y-m-d');

        $dwa = DWA::select('id')->where('site_id', '=', $site_id)->where('machine_id', '=', $machine_id)
            ->where('activity_date', '=', $current_date)->first();

        return $dwa->id;
    }

    //check available activity time
    private function checkAvailableActivityTime($dwa_id, $start_time, $end_time)
    {
        $count_activities = DWAActivity::where('dwa_id', '=', $dwa_id)->count();

        $activities = DWAActivity::where('dwa_id', '=', $dwa_id)
            ->whereRaw('(((? < start_time) AND (? <= start_time)) OR ((? >= end_time) AND (? > end_time)))',
                array($start_time, $end_time, $start_time, $end_time))->count();

        //if time is not available return error message
        if ($count_activities != $activities)
        {
            return array('status' => 0, 'error' => trans('errors.activity_available_time'));
        }

        return ['status' => 1];
    }

    //save activity
    public function saveActivity($is_edit, $dwa_id, $site_id, $employee_id, $machine_id, $start_time, $end_time, $tool_id, $activity,
        $start_working_hours, $end_working_hours)
    {
        try
        {
            //get current date
            $current_date = date('Y-m-d');

            if ($is_edit == 'F')
            {
                //call getDWAId method to get dwa id
                $dwa_id = $this->getDWAId($site_id, $machine_id);
            }
            else
            {
                //get current date
                $current_date = DWA::find($dwa_id)->activity_date;
            }

            //call isDWAConfirmed method to check if dwa is confirmed
            $confirmed_dwa = $this->isDWAConfirmed(null, $dwa_id);

            if ($confirmed_dwa)
            {
                //if dwa is confirmed return warning message
                return ['status' => 3, 'info' => trans('main.dwa_confirmed_info')];
            }

            //call getUserEmployeeId method from EmployeeRepository to get creator id
            $repo = new EmployeeRepository;
            $creator_id = $repo->getUserEmployeeId();

            //format start and end time
            $start_time = date('Y-m-d H:i:s', strtotime($current_date.' '.$start_time.':00'));
            $end_time = date('Y-m-d H:i:s', strtotime($current_date.' '.$end_time.':00'));

            //call checkAvailableActivityTime method to check available activity time
            $response = $this->checkAvailableActivityTime($dwa_id, $start_time, $end_time);

            if ($response['status'] == 0)
            {
                return $response;
            }

            $dwa = new DWAActivity;
            $dwa->dwa_id = $dwa_id;
            $dwa->creator_id = $creator_id;
            $dwa->employee_id = $employee_id;
            $dwa->tool_id = $tool_id;
            $dwa->activity_id = $activity;
            $dwa->start_time = $start_time;
            $dwa->end_time = $end_time;
            $dwa->start_working_hours = $start_working_hours;
            $dwa->end_working_hours = $end_working_hours;
            $dwa->save();

            return ['status' => 1, 'success' => trans('main.activity_insert')];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //save fuel
    public function saveFuel($is_edit, $dwa_id, $site_id, $employee_id, $machine_id, $quantity, $invoice_number)
    {
        try
        {
            if ($is_edit == 'F')
            {
                //call getDWAId method to get dwa id
                $dwa_id = $this->getDWAId($site_id, $machine_id);
            }

            //call isDWAConfirmed method to check if dwa is confirmed
            $confirmed_dwa = $this->isDWAConfirmed(null, $dwa_id);

            if ($confirmed_dwa)
            {
                //if dwa is confirmed return warning message
                return ['status' => 3, 'info' => trans('main.dwa_confirmed_info')];
            }

            //call getUserEmployeeId method from EmployeeRepository to get creator id
            $repo = new EmployeeRepository;
            $creator_id = $repo->getUserEmployeeId();

            $dwa = new DWAFuel;
            $dwa->dwa_id = $dwa_id;
            $dwa->creator_id = $creator_id;
            $dwa->employee_id = $employee_id;
            $dwa->quantity = $quantity;
            $dwa->invoice_number = $invoice_number;
            $dwa->save();

            return ['status' => 1, 'success' => trans('main.fuel_insert')];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //save fluid
    public function saveFluid($is_edit, $dwa_id, $site_id, $employee_id, $machine_id, $component, $quantity)
    {
        try
        {
            if ($is_edit == 'F')
            {
                //call getDWAId method to get dwa id
                $dwa_id = $this->getDWAId($site_id, $machine_id);
            }

            //call isDWAConfirmed method to check if dwa is confirmed
            $confirmed_dwa = $this->isDWAConfirmed(null, $dwa_id);

            if ($confirmed_dwa)
            {
                //if dwa is confirmed return warning message
                return ['status' => 3, 'info' => trans('main.dwa_confirmed_info')];
            }

            //call getUserEmployeeId method from EmployeeRepository to get creator id
            $repo = new EmployeeRepository;
            $creator_id = $repo->getUserEmployeeId();

            $dwa = new DWAFluid;
            $dwa->dwa_id = $dwa_id;
            $dwa->creator_id = $creator_id;
            $dwa->employee_id = $employee_id;
            $dwa->component_id = $component;
            $dwa->quantity = $quantity;
            $dwa->save();

            return ['status' => 1, 'success' => trans('main.fluid_insert')];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //save filter
    public function saveFilter($is_edit, $dwa_id, $site_id, $employee_id, $machine_id, $component, $quantity)
    {
        try
        {
            if ($is_edit == 'F')
            {
                //call getDWAId method to get dwa id
                $dwa_id = $this->getDWAId($site_id, $machine_id);
            }

            //call isDWAConfirmed method to check if dwa is confirmed
            $confirmed_dwa = $this->isDWAConfirmed(null, $dwa_id);

            if ($confirmed_dwa)
            {
                //if dwa is confirmed return warning message
                return ['status' => 3, 'info' => trans('main.dwa_confirmed_info')];
            }

            //call getUserEmployeeId method from EmployeeRepository to get creator id
            $repo = new EmployeeRepository;
            $creator_id = $repo->getUserEmployeeId();

            $dwa = new DWAFilter;
            $dwa->dwa_id = $dwa_id;
            $dwa->creator_id = $creator_id;
            $dwa->employee_id = $employee_id;
            $dwa->component_id = $component;
            $dwa->quantity = $quantity;
            $dwa->save();

            return ['status' => 1, 'success' => trans('main.filter_insert')];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //save note
    public function saveNote($is_edit, $dwa_id, $site_id, $employee_id, $machine_id, $note, $photo)
    {
        try
        {
            if ($is_edit == 'F')
            {
                //call getDWAId method to get dwa id
                $dwa_id = $this->getDWAId($site_id, $machine_id);
            }

            //call isDWAConfirmed method to check if dwa is confirmed
            $confirmed_dwa = $this->isDWAConfirmed(null, $dwa_id);

            if ($confirmed_dwa)
            {
                //if dwa is confirmed return warning message
                return ['status' => 3, 'info' => trans('main.dwa_confirmed_info')];
            }

            //call getUserEmployeeId method from EmployeeRepository to get creator id
            $repo = new EmployeeRepository;
            $creator_id = $repo->getUserEmployeeId();

            //start transaction
            DB::beginTransaction();

            $dwa = new DWANote;
            $dwa->dwa_id = $dwa_id;
            $dwa->creator_id = $creator_id;
            $dwa->employee_id = $employee_id;
            $dwa->note = $note;
            $dwa->save();

            if ($photo)
            {
                //call uploadPhoto method from PictureRepository to upload photo
                $repo = new PictureRepository;
                $upload = $repo->uploadPhoto($photo);

                //if response status = 0 return error message
                if ($upload['status'] == 0)
                {
                    return ['status' => 0];
                }

                //save photo
                $dwa->photo = $upload['data'];
                $dwa->save();
            }

            //commit transaction
            DB::commit();

            return ['status' => 1, 'success' => trans('main.note_insert')];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //get activities
    public function getActivities($dwa_id)
    {
        try
        {
            //set activities array
            $activities_array = [];

            //set hours sum
            $hours_sum = 0;

            //set working hours sum
            $working_hours_sum = 0;

            $activities = DWAActivity::with('employee', 'tool', 'activity')
                ->select('id', 'employee_id', 'tool_id', 'activity_id', DB::raw('DATE_FORMAT(start_time, "%H:%i") AS start_hour'),
                    DB::raw('DATE_FORMAT(end_time, "%H:%i") AS end_hour'),
                    DB::raw('TIME_TO_SEC(TIMEDIFF(end_time, start_time))/3600 as hours'), 'start_working_hours', 'end_working_hours')
                ->where('dwa_id', '=', $dwa_id)->get();

            foreach ($activities as $activity)
            {
                $tool = '';

                if ($activity->tool)
                {
                    $tool = $activity->tool->name;
                }

                $working_hours = $activity->end_working_hours - $activity->start_working_hours;

                //add activity to activities array
                $activities_array[] = ['id' => $activity->id, 'employee' => $activity->employee->name, 'start_hour' => $activity->start_hour,
                    'end_hour' => $activity->end_hour, 'activity' => $activity->activity->name, 'tool' => $tool,
                    'hours' => floatval($activity->hours), 'start_working_hours' => $activity->start_working_hours,
                    'end_working_hours' => $activity->end_working_hours, 'working_hours' => $working_hours];

                $hours_sum += floatval($activity->hours);
                $working_hours_sum += $working_hours;
            }

            return ['status' => 1, 'activities' => $activities_array, 'hours_sum' => $hours_sum, 'working_hours_sum' => $working_hours_sum];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //get fuel
    public function getFuel($dwa_id)
    {
        try
        {
            //set fuel array
            $fuel_array = [];

            //set fuel sum
            $fuel_sum = 0;

            $fuel = DWAFuel::with('employee')
                ->select('id', 'employee_id', 'quantity', 'invoice_number')->where('dwa_id', '=', $dwa_id)->get();

            foreach ($fuel as $fuel_data)
            {
                //add fuel to fuel array
                $fuel_array[] = ['id' => $fuel_data->id, 'employee' => $fuel_data->employee->name, 'quantity' => $fuel_data->quantity,
                    'invoice_number' => $fuel_data->invoice_number];

                $fuel_sum += $fuel_data->quantity;
            }

            return ['status' => 1, 'fuel' => $fuel_array, 'fuel_sum' => $fuel_sum];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //get fluids
    public function getFluids($dwa_id)
    {
        try
        {
            //set fluids array
            $fluids_array = [];

            //set fluids sum
            $fluids_sum = 0;

            $fluids = DWAFluid::with('employee', 'component')
                ->select('id', 'employee_id', 'component_id', 'quantity')->where('dwa_id', '=', $dwa_id)->get();

            foreach ($fluids as $fluid)
            {
                //add fluid to fluid array
                $fluids_array[] = ['id' => $fluid->id,'employee' => $fluid->employee->name, 'component' => $fluid->component->name,
                    'quantity' => $fluid->quantity];

                $fluids_sum += $fluid->quantity;
            }

            return ['status' => 1, 'fluids' => $fluids_array, 'fluids_sum' => $fluids_sum];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //get filters
    public function getFilters($dwa_id)
    {
        try
        {
            //set filters array
            $filters_array = [];

            //set filters sum
            $filters_sum = 0;

            $filters = DWAFilter::with('employee', 'component')
                ->select('id', 'employee_id', 'component_id', 'quantity')->where('dwa_id', '=', $dwa_id)->get();

            foreach ($filters as $filter)
            {
                //add filter to filter array
                $filters_array[] = ['id' => $filter->id,'employee' => $filter->employee->name, 'component' => $filter->component->name,
                    'quantity' => $filter->quantity];

                $filters_sum += $filter->quantity;
            }

            return ['status' => 1, 'filters' => $filters_array, 'filters_sum' => $filters_sum];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //get notes
    public function getNotes($dwa_id)
    {
        try
        {
            //set notes array
            $notes_array = [];

            $notes = DWANote::with('employee')
                ->select('employee_id', 'note', 'photo')->where('dwa_id', '=', $dwa_id)->get();

            foreach ($notes as $note)
            {
                $photo = '';

                if ($note->photo)
                {
                    $photo = URL::to('/').'/laravel/storage/app/public/photos/'.$note->photo;
                }

                //add note to notes array
                $notes_array[] = ['employee' => $note->employee->name, 'note' => $note->note, 'photo' => $photo];
            }

            return ['status' => 1, 'notes' => $notes_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0, 'error' => trans('errors.error')];
        }
    }

    //get sites and machines filters
    private function filterEmployeeSitesAndMachines($employee_id, $work_type)
    {
        try
        {
            //set filter sites array
            $filter_sites_array = [];

            //set filter machines array
            $filter_machines_array = [];

            //set machines array
            $machines_array = [];

            if ($work_type == 4 || $work_type == 5)
            {
                //call getSitesSelect method from SiteRepository to get sites - select
                $repo = new SiteRepository;
                $sites = $repo->getSitesSelect();

                //if response status = 0 return error message
                if ($sites['status'] == 0)
                {
                    return ['status' => 0];
                }

                $filter_sites_array = $sites['data'];

                //call getMachinesSelect method from MachineRepository to get machines - select
                $repo = new MachineRepository;
                $machines = $repo->getMachinesSelect(1);

                //if response status = 0 return error message
                if ($machines['status'] == 0)
                {
                    return ['status' => 0];
                }

                $filter_machines_array = $machines['data'];
            }
            else
            {
                //get employee unique machines of fuel entries
                $fuel_machines = DWA::select('machine_id')->whereHas('fuel', function($query) use ($employee_id) {
                    $query->where('employee_id', '=', $employee_id);
                })->distinct()->get();

                foreach ($fuel_machines as $fuel_machine)
                {
                    //add machine to machines array
                    $machines_array[] = $fuel_machine->machine_id;
                }

                //get employee unique machines of fluid entries
                $fluid_machines = DWA::select('machine_id')->whereHas('fluids', function($query) use ($employee_id) {
                    $query->where('employee_id', '=', $employee_id);
                })->whereNotIn('machine_id', $machines_array)->distinct()->get();

                foreach ($fluid_machines as $fluid_machine)
                {
                    //add machine to machines array
                    $machines_array[] = $fluid_machine->machine_id;
                }

                //get employee unique machines of filter entries
                $filter_machines = DWA::select('machine_id')->whereHas('filters', function($query) use ($employee_id) {
                    $query->where('employee_id', '=', $employee_id);
                })->whereNotIn('machine_id', $machines_array)->distinct()->get();

                foreach ($filter_machines as $filter_machine)
                {
                    //add machine to machines array
                    $machines_array[] = $filter_machine->machine_id;
                }

                if ($work_type == 1)
                {
                    //get employee unique machines of creator or activity entries
                    $activity_machines = DWA::select('machine_id')->where(function($query) use ($employee_id) {
                        $query->whereHas('activities', function($query2) use ($employee_id) {
                            $query2->where('employee_id', '=', $employee_id);
                        })->orWhere('creator_id', '=', $employee_id);
                    })->whereNotIn('machine_id', $machines_array)->distinct()->get();

                    foreach ($activity_machines as $activity_machine)
                    {
                        //add machine to machines array
                        $machines_array[] = $activity_machine->machine_id;
                    }
                }

                if ($work_type != 3)
                {
                    //get employee unique machines of note entries
                    $note_machines = DWA::select('machine_id')->whereHas('notes', function($query) use ($employee_id) {
                        $query->where('employee_id', '=', $employee_id);
                    })->whereNotIn('machine_id', $machines_array)->distinct()->get();

                    foreach ($note_machines as $note_machine)
                    {
                        //add machine to machines array
                        $machines_array[] = $note_machine->machine_id;
                    }
                }

                //add default option to filter machines array
                $filter_machines_array[0] = trans('main.choose_machine');

                //create machine filter
                foreach ($machines_array as $machine)
                {
                    //add machine to filter machines array
                    $filter_machines_array[$machine] = Machine::find($machine)->name;
                }
            }

            return ['status' => 1, 'filter_sites' => $filter_sites_array, 'filter_machines' => $filter_machines_array,
                'machines' => $machines_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get dwa details
    public function getDWADetails($id)
    {
        try
        {
            $dwa = DWA::with('site', 'machine')
                ->select('id', 'site_id', 'machine_id', 'activity_date', 'machine_checked', 'damage', 'confirmation_head_of_site_id',
                    'confirmation_manager_id')->where('id', '=', $id)->first();

            //if dwa doesn't exist return error message
            if (!$dwa)
            {
                return ['status' => 0];
            }

            /*
            |--------------------------------------------------------------------------
            | Check confirmation
            |--------------------------------------------------------------------------
            */

            //call checkDWAConfirmation method to check dwa confirmation
            $confirmation = $this->checkDWAConfirmation($dwa);

            //if response status = 0 return error message
            if ($confirmation['status'] == 0)
            {
                return ['status' => 0];
            }

            //set site name
            $dwa->site_name = $dwa->site->name;

            //set machine name
            $dwa->machine_name = $dwa->machine->name;

            //format activity date
            $dwa->activity_date = date('d.m.Y.', strtotime($dwa->activity_date));

            if ($dwa->machine_checked == 'T')
            {
                $dwa->machine_checked = trans('main.yes');
            }
            else
            {
                $dwa->machine_checked = trans('main.no');
            }

            if ($dwa->damage == 'T')
            {
                $dwa->damage = trans('main.yes');
            }
            else
            {
                $dwa->damage = trans('main.no');
            }

            return ['status' => 1, 'dwa' => $dwa, 'confirmation' => $confirmation];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //check dwa confirmation
    private function checkDWAConfirmation($dwa)
    {
        try
        {
            //set head of site confirmation variable
            $head_of_site_confirmation = 'F';

            //set manager confirmation variable
            $manager_confirmation = 'F';

            //set can confirm variable
            $can_confirm = 'F';

            //call getAuthenticatedUser method from UserRepository to get authenticated user
            $repo = new UserRepository;
            $user = $repo->getAuthenticatedUser();

            //call getUserEmployeeId method from EmployeeRepository to get employee id
            $repo = new EmployeeRepository;
            $employee_id = $repo->getUserEmployeeId();

            //call getCurrentUserHistorySite method from ManipulationRepository to get current user history site
            $repo = new ManipulationRepository;
            $current_site = $repo->getCurrentUserHistorySite($user, $dwa->site_id, $dwa->activity_date);

            //check if dwa is confirmed by head of site
            if ($dwa->confirmation_head_of_site_id)
            {
                //set head of site confirmation variable to 'T'
                $head_of_site_confirmation = 'T';
            }
            else
            {
                if ($user->hasRole('HeadOfSite') && $dwa->site_id == $current_site)
                {
                    //set can confirm variable to 'T'
                    $can_confirm = 'T';
                }
            }

            //check if dwa is confirmed by manager
            if ($dwa->confirmation_manager_id)
            {
                //set manager confirmation variable to 'T'
                $manager_confirmation = 'T';
            }
            else
            {
                if ($user->hasRole('Manager') && $dwa->site_id == $current_site)
                {
                    //set can confirm variable to 'T'
                    $can_confirm = 'T';
                }
            }

            //get current date
            $current_date = date('Y-m-d');

            //add three days to dwa activity date
            $activity_date = date('Y-m-d', strtotime('+3 day', strtotime($dwa->activity_date)));

            //if dwa activity date is older than three days set can confirm variable to 'F'
            if ($current_date > $activity_date)
            {
                $can_confirm = 'F';
            }

            return ['status' => 1, 'head_of_site_confirmation' => $head_of_site_confirmation, 'manager_confirmation' => $manager_confirmation,
                'can_confirm' => $can_confirm, 'employee_id' => $employee_id];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //confirm dwa
    public function confirmDWA($id)
    {
        try
        {
            $dwa = DWA::find($id);

            //if dwa doesn't exist return error message
            if (!$dwa)
            {
                return ['status' => 0];
            }

            //call checkDWAConfirmation method to check dwa confirmation
            $confirmation = $this->checkDWAConfirmation($dwa);

            //if response status = 0 return error message
            if ($confirmation['status'] == 0)
            {
                return ['status' => 0];
            }

            //if can confirm response = 'F' return error message
            if ($confirmation['can_confirm'] == 'F')
            {
                return ['status' => 2];
            }

            if ($confirmation['head_of_site_confirmation'] == 'F')
            {
                $dwa->confirmation_head_of_site_id = $confirmation['employee_id'];
            }
            else
            {
                $dwa->confirmation_manager_id = $confirmation['employee_id'];
            }

            $dwa->save();

            return ['status' => 1, 'dwa' => $dwa, 'confirmation' => $confirmation];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //check is daily work activity confirmed
    public function isDWAConfirmed($machine_id, $dwa_id)
    {
        $confirmed_dwa = DWA::where(function($query) {
            $query->whereNotNull('confirmation_manager_id')->orWhereNotNull('confirmation_head_of_site_id');
        });

        if ($dwa_id)
        {
            $confirmed_dwa->where('id', '=', $dwa_id);
        }
        else
        {
            //get current date
            $current_date = date('Y-m-d');

            $confirmed_dwa->where('machine_id', '=', $machine_id)->where('activity_date', '=', $current_date);
        }

        $confirmed_dwa = $confirmed_dwa->first();

        return $confirmed_dwa;
    }

    //delete activity
    public function deleteActivity($dwa_id, $activity_id)
    {
        try
        {
            $activity = DWAActivity::where('dwa_id', '=', $dwa_id)->where('id', '=', $activity_id)->first();

            //if activity doesn't exist return error message
            if (!$activity)
            {
                return ['status' => 0];
            }

            $dwa = DWA::find($dwa_id);

            //call getAuthenticatedUser method from UserRepository to get authenticated user
            $repo = new UserRepository;
            $user = $repo->getAuthenticatedUser();

            //call getCurrentUserHistorySite method from ManipulationRepository to get current user history site
            $repo = new ManipulationRepository;
            $current_site = $repo->getCurrentUserHistorySite($user, $dwa->site_id, $dwa->activity_date);

            if ($dwa->site_id != $current_site)
            {
                //if dwa site id is not equal head of site or manager site id return warning message
                return ['status' => 2, 'warning' => trans('errors.dwa_edit_not_allowed')];
            }

            //delete activity
            $activity->delete();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //delete fuel
    public function deleteFuel($dwa_id, $fuel_id)
    {
        try
        {
            $fuel = DWAFuel::where('dwa_id', '=', $dwa_id)->where('id', '=', $fuel_id)->first();

            //if fuel doesn't exist return error message
            if (!$fuel)
            {
                return ['status' => 0];
            }

            $dwa = DWA::find($dwa_id);

            //call getAuthenticatedUser method from UserRepository to get authenticated user
            $repo = new UserRepository;
            $user = $repo->getAuthenticatedUser();

            //call getCurrentUserHistorySite method from ManipulationRepository to get current user history site
            $repo = new ManipulationRepository;
            $current_site = $repo->getCurrentUserHistorySite($user, $dwa->site_id, $dwa->activity_date);

            if ($dwa->site_id != $current_site)
            {
                //if dwa site id is not equal head of site or manager site id return warning message
                return ['status' => 2, 'warning' => trans('errors.dwa_edit_not_allowed')];
            }

            //delete fuel
            $fuel->delete();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //delete fluid
    public function deleteFluid($dwa_id, $fluid_id)
    {
        try
        {
            $fluid = DWAFluid::where('dwa_id', '=', $dwa_id)->where('id', '=', $fluid_id)->first();

            //if fluid doesn't exist return error message
            if (!$fluid)
            {
                return ['status' => 0];
            }

            $dwa = DWA::find($dwa_id);

            //call getAuthenticatedUser method from UserRepository to get authenticated user
            $repo = new UserRepository;
            $user = $repo->getAuthenticatedUser();

            //call getCurrentUserHistorySite method from ManipulationRepository to get current user history site
            $repo = new ManipulationRepository;
            $current_site = $repo->getCurrentUserHistorySite($user, $dwa->site_id, $dwa->activity_date);

            if ($dwa->site_id != $current_site)
            {
                //if dwa site id is not equal head of site or manager site id return warning message
                return ['status' => 2, 'warning' => trans('errors.dwa_edit_not_allowed')];
            }

            //delete fluid
            $fluid->delete();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //delete filter
    public function deleteFilter($dwa_id, $filter_id)
    {
        try
        {
            $filter = DWAFilter::where('dwa_id', '=', $dwa_id)->where('id', '=', $filter_id)->first();

            //if filter doesn't exist return error message
            if (!$filter)
            {
                return ['status' => 0];
            }

            $dwa = DWA::find($dwa_id);

            //call getAuthenticatedUser method from UserRepository to get authenticated user
            $repo = new UserRepository;
            $user = $repo->getAuthenticatedUser();

            //call getCurrentUserHistorySite method from ManipulationRepository to get current user history site
            $repo = new ManipulationRepository;
            $current_site = $repo->getCurrentUserHistorySite($user, $dwa->site_id, $dwa->activity_date);

            if ($dwa->site_id != $current_site)
            {
                //if dwa site id is not equal head of site or manager site id return warning message
                return ['status' => 2, 'warning' => trans('errors.dwa_edit_not_allowed')];
            }

            //delete filter
            $filter->delete();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }
}
