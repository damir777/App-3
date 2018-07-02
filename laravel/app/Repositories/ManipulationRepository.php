<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Machine;
use App\Tool;
use App\Equipment;
use App\Vehicle;
use App\Employee;
use App\Site;
use App\Parking;
use App\SiteManipulation;
use App\ParkingManipulation;
use App\EmployeeManipulation;
use App\AdditionalManipulation;
use App\DWA;

class ManipulationRepository
{
    //get dashboard data
    public function getDashboardData($get_sites, $get_resources, $employee_type, $resource_type, $list_type, $page, $search_string,
        $search_filter, $site_id, $parking_id)
    {
        try
        {
            //set default sites, resources and employees
            $sites = null;
            $parking = null;
            $resources = null;
            $employees = null;

            //if get sites parameter = 'T' get active sites and active parking
            if ($get_sites == 'T')
            {
                //call getActiveSites method to get active sites
                $site_response = $this->getActiveSites('T');

                //if sites response status = 0 return error message
                if ($site_response['status'] == 0)
                {
                    return ['status' => 0];
                }

                $sites = $site_response['data'];

                //call getActiveParking method to get active parking
                $parking_response = $this->getActiveParking('T');

                //if parking response status = 0 return error message
                if ($parking_response['status'] == 0)
                {
                    return ['status' => 0];
                }

                $parking = $parking_response['data'];
            }

            //if get resources parameter = 'T' get resources
            if ($get_resources == 'T')
            {
                //call getResources method to get resources
                $resources_response = $this->getResources($resource_type, $list_type, $page, $search_string, $search_filter, $site_id,
                    $parking_id);

                //if resources response status = 0 return error message
                if ($resources_response['status'] == 0)
                {
                    return ['status' => 0];
                }

                $resources = $resources_response;

                //if list type parameter = 'null' or list type = 'employees' get employees
                if (!$list_type || $list_type == 'employees')
                {
                    //call getEmployees method to get employees
                    $employees_response = $this->getEmployees($employee_type, $page, $search_string);

                    //if employees response status = 0 return error message
                    if ($employees_response['status'] == 0)
                    {
                        return ['status' => 0];
                    }

                    $employees = $employees_response;
                }
            }

            return ['status' => 1, 'sites' => $sites, 'employees' => $employees, 'resources' => $resources, 'parking' => $parking];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get active sites
    public function getActiveSites($include_statistic, $exclude_id = false)
    {
        try
        {
            $sites = Site::with('investor')
                ->select('id', 'name', 'city', 'address', 'investor_id', 'start_date', 'plan_end_date', 'latitude', 'longitude')
                ->where('status_id', '=', 1);

            if ($exclude_id)
            {
                $sites->where('id', '!=', $exclude_id);
            }

            $sites = $sites->orderBy('id', 'asc')->get();

            //set sites array
            $sites_array = [];

            //set default resources variables
            $machines = 0;
            $tools = 0;
            $equipment = 0;
            $vehicles = 0;
            $employees = 0;

            foreach ($sites as $site)
            {
                $start_date = date('d.m.Y.', strtotime($site->start_date));
                $plan_end_date = date('d.m.Y.', strtotime($site->plan_end_date));

                if ($include_statistic == 'T')
                {
                    //count all site resources
                    $machines = SiteManipulation::with('machine')
                        ->whereHas('machine', function($query) {
                            $query->where('status_id', '=', 1);
                        })
                        ->where('site_id', '=', $site->id)->where('resource_type', '=', 1)->whereNull('end_time')->count();

                    $tools = SiteManipulation::with('tool')
                        ->whereHas('tool', function($query) {
                            $query->where('status_id', '=', 1);
                        })
                        ->where('site_id', '=', $site->id)->where('resource_type', '=', 2)->whereNull('end_time')->count();

                    $equipment = SiteManipulation::with('equipment')
                        ->whereHas('equipment', function($query) {
                            $query->where('status_id', '=', 1);
                        })
                        ->where('site_id', '=', $site->id)->where('resource_type', '=', 3)->whereNull('end_time')->count();

                    $vehicles = SiteManipulation::with('vehicle')
                        ->whereHas('vehicle', function($query) {
                            $query->where('status_id', '=', 1);
                        })
                        ->where('site_id', '=', $site->id)->where('resource_type', '=', 4)->whereNull('end_time')->count();

                    $employees = SiteManipulation::with('employee')
                        ->whereHas('employee', function($query) {
                            $query->where('status_id', '=', 1);
                        })
                        ->where('site_id', '=', $site->id)->where('resource_type', '=', 5)->whereNull('end_time')->count();
                }

                $sites_array[] = ['id' => $site->id, 'name' => htmlspecialchars($site->name), 'city' => $site->city,
                    'address' => $site->address, 'investor' => trans('main.investor').': '.$site->investor->name,
                    'start_date_trans' => trans('main.work_start_date'), 'plan_end_date_trans' => trans('main.plan_end_date'),
                    'start_date' => $start_date, 'plan_end_date' => $plan_end_date, 'latitude' => $site->latitude,
                    'longitude' => $site->longitude, 'machines' => $machines, 'tools' => $tools, 'equipment' => $equipment,
                    'vehicles' => $vehicles, 'employees' => $employees];
            }

            return ['status' => 1, 'data' => $sites_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get active parking
    public function getActiveParking($include_statistic, $exclude_id = false)
    {
        try
        {
            $parking = Parking::select('id', 'name', 'address', 'latitude', 'longitude')->where('status_id', '=', 1);

            if ($exclude_id)
            {
                $parking->where('id', '!=', $exclude_id);
            }

            $parking = $parking->orderBy('id', 'asc')->get();

            //set parking array
            $parking_array = [];

            //set default resources variables
            $machines = 0;
            $tools = 0;
            $equipment = 0;
            $vehicles = 0;

            foreach ($parking as $parking_data)
            {
                if ($include_statistic == 'T')
                {
                    //count all parking resources
                    $machines = ParkingManipulation::where('parking_id', '=', $parking_data->id)->where('resource_type', '=', 1)
                        ->whereNull('end_time')->count();

                    $tools = ParkingManipulation::where('parking_id', '=', $parking_data->id)->where('resource_type', '=', 2)
                        ->whereNull('end_time')->count();

                    $equipment = ParkingManipulation::where('parking_id', '=', $parking_data->id)->where('resource_type', '=', 3)
                        ->whereNull('end_time')->count();

                    $vehicles = ParkingManipulation::where('parking_id', '=', $parking_data->id)->where('resource_type', '=', 4)
                        ->whereNull('end_time')->count();
                }

                $parking_array[] = ['id' => $parking_data->id, 'name' => htmlspecialchars($parking_data->name),
                    'address' => $parking_data->address, 'latitude' => $parking_data->latitude, 'longitude' => $parking_data->longitude,
                    'machines' => $machines, 'tools' => $tools, 'equipment' => $equipment, 'vehicles' => $vehicles];
            }

            return ['status' => 1, 'data' => $parking_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get employees
    public function getEmployees($employee_type, $page, $search_string)
    {
        try
        {
            //set max employees per page
            $max_employees = 10;

            //set employees array
            $employees_array = [];

            //set status names array
            $status_names_array = ['Godišnji', 'Bolovanje', 'Slobodni dani'];

            //set status types array
            $status_types_array = [];

            //table table header array
            $table_header = [trans('main.picture'), trans('main.employee_code'), trans('main.employee_name'), trans('main.work_type'),
                trans('main.oib'), trans('main.site'), trans('main.status')];

            //set picture path
            $picture_path = URL::to('/').'/laravel/storage/app/public/employees/';

            //get employees
            $employees = Employee::with('workType')->select('id', 'code', 'work_type_id', 'name', 'oib', 'picture')
                ->where('status_id', '=', 1)->where('id', '!=', 1);

            //filter subjects which are on selected site
            $employees->whereIn('id', function($query) use ($employee_type) {
                $query->select('employee_id')
                    ->from('employee_manipulations')
                    ->where('type_id', '=', $employee_type)
                    ->whereNull('end_time');
            });

            if ($search_string != null)
            {
                $employees->where(function($query) use ($search_string) {
                    $query->where('code', 'like', '%'.$search_string.'%')
                        ->orWhere('name', 'like', '%'.$search_string.'%');
                });
            }

            //count employees
            $count_employees = $employees->count();

            //add max employees filter
            $employees->take($max_employees);

            if ($page != 1)
            {
                $skip = ($page - 1) * $max_employees;

                $employees->skip($skip);

                $paginate = 'T';

                if (($skip + $max_employees) < $count_employees)
                {
                    $previous_pagination = 'T';
                    $next_pagination = 'T';
                }
                else
                {
                    $previous_pagination = 'T';
                    $next_pagination = 'F';
                }
            }
            else
            {
                if ($count_employees > $max_employees)
                {
                    $paginate = 'T';
                    $previous_pagination = 'F';
                    $next_pagination = 'T';
                }
                else
                {
                    $paginate = 'F';
                    $previous_pagination = 'F';
                    $next_pagination = 'F';
                }
            }

            $employees = $employees->orderBy('id')->get();

            foreach ($employees as $employee)
            {
                //add employee to employees array
                $employees_array[] = ['id' => $employee->id, 'code' => $employee->code, 'name' => $employee->name,
                    'work_type' =>$employee->workType->name, 'picture' => $picture_path.$employee->picture, 'oib' => $employee->oib,
                    'route' => route('EditEmployee', $employee->id)];
            }

            //set employee types array
            for ($i = 1; $i < 4; $i++)
            {
                //if i != employee type add type to employee types array
                if ($i != $employee_type)
                {
                    $status_types_array[] = ['id' => $i, 'name' => $status_names_array[$i - 1]];
                }
            }

            //get active sites
            $active_sites = $this->getActiveSites('F');
            $sites = $active_sites['data'];

            return ['status' => 1, 'employees' => $employees_array, 'table_header' => $table_header, 'paginate' => $paginate,
                'previous_pagination' => $previous_pagination, 'next_pagination' => $next_pagination, 'page' => $page,
                'status_types' => $status_types_array, 'sites' => $sites];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get resources
    public function getResources($resource_type, $list_type, $page, $search_string, $search_filter, $site_id, $parking_id)
    {
        try
        {
            //set max resources per page
            $max_resources = 10;

            //set resources array
            $resources_array = [];

            //set filter options variable
            $filter_options = null;

            //set resources models array
            $resource_models_array = [
                1 => ['model' => Machine::with('manufacturer')->select('id', 'code', 'manufacturer_id', 'name', 'model', 'picture'),
                    'name' => 'machines', 'route' => 'EditMachine'],
                2 => ['model' => Tool::with('manufacturer')->select('id', 'code', 'manufacturer_id', 'name', 'model', 'picture'),
                    'name' => 'tools', 'route' => 'EditTool'],
                3 => ['model' => Equipment::with('manufacturer')->select('id', 'code', 'manufacturer_id', 'name', 'model', 'picture'),
                    'name' => 'equipment', 'route' => 'EditEquipment'],
                4 => ['model' => Vehicle::with('manufacturer')->select('id', 'code', 'manufacturer_id', 'name', 'model', 'picture'),
                    'name' => 'vehicles', 'route' => 'EditVehicle'],
                5 => ['model' => Employee::with('workType')->select('id', 'code', 'work_type_id', 'name', 'oib', 'picture'),
                    'name' => 'employees', 'route' => 'EditEmployee']];

            //set table headers array
            $headers_array = [
                1 => [trans('main.picture'), trans('main.code'), trans('main.manufacturer'), trans('main.name'), trans('main.model'),
                    trans('main.site'), trans('main.parking_single')],
                5 => [trans('main.picture'), trans('main.employee_code'), trans('main.employee_name'), trans('main.work_type'),
                    trans('main.oib'), trans('main.site'), trans('main.status')]];

            //set table headers
            if ($resource_type != 5)
            {
                $header = $headers_array[1];
            }
            else
            {
                $header = $headers_array[$resource_type];
            }

            //set picture path
            $picture_path = URL::to('/').'/laravel/storage/app/public/'.$resource_models_array[$resource_type]['name'].'/';

            //get resource model
            $resources = $resource_models_array[$resource_type]['model'];

            //add where condition for active status
            $resources->where('status_id', '=', 1);

            //if resource type = '5' exclude first admin
            if ($resource_type == 5)
            {
                $resources->where('id', '!=', 1);
            }

            if (!$list_type || $list_type == 'active')
            {
                /*
                |--------------------------------------------------------------------------
                | Get all resources which are not on any site or parking
                |--------------------------------------------------------------------------
                */

                //filter resources which are not on any site
                $resources->whereNotIn('id', function($query) use ($resource_type) {
                    $query->select('resource_id')
                        ->from('site_manipulations')
                        ->where('resource_type', '=', $resource_type)
                        ->whereNull('end_time');
                    });

                if ($resource_type == 5)
                {
                    //filter employees which who have some status
                    $resources->whereNotIn('id', function($query) use ($resource_type) {
                        $query->select('employee_id')
                            ->from('employee_manipulations')
                            ->whereNull('end_time');
                    });
                }

                //filter resources which are not on any parking
                $resources->whereNotIn('id', function($query) use ($resource_type) {
                    $query->select('resource_id')
                        ->from('parking_manipulations')
                        ->where('resource_type', '=', $resource_type)
                        ->whereNull('end_time');
                });
            }
            else
            {
                /*
                |--------------------------------------------------------------------------
                | Get all resources on selected site or parking
                |--------------------------------------------------------------------------
                */

                if ($site_id)
                {
                    //filter resources which are on selected site
                    $resources->whereIn('id', function($query) use ($site_id, $resource_type) {
                        $query->select('resource_id')
                            ->from('site_manipulations')
                            ->where('site_id', '=', $site_id)
                            ->where('resource_type', '=', $resource_type)
                            ->whereNull('end_time');
                    });
                }
                else
                {
                    //filter resources which are on selected parking
                    $resources->whereIn('id', function($query) use ($parking_id, $resource_type) {
                        $query->select('resource_id')
                            ->from('parking_manipulations')
                            ->where('parking_id', '=', $parking_id)
                            ->where('resource_type', '=', $resource_type)
                            ->whereNull('end_time');
                    });
                }

                if ($search_filter != 0)
                {
                    //set filter columns array
                    $filter_columns = [1 => 'machine_type_id', 2 => 'tool_type_id', 3 => 'equipment_type_id', 4 => 'vehicle_type_id',
                        5 => 'work_type_id'];

                    $resources->where($filter_columns[$resource_type], '=', $search_filter);
                }

                //call getGeneralTypesSelect method from GeneralRepository to get resource types
                $repo = new GeneralRepository;
                $response = $repo->getGeneralTypesSelect($resource_type, 1);

                //if response status = 0 return error message
                if ($response['status'] == 0)
                {
                    return ['status' => 0];
                }

                $filter_options = $response['data'];
            }

            if ($search_string != null)
            {
                $resources->where(function($query) use ($search_string) {
                    $query->where('code', 'like', '%'.$search_string.'%')
                        ->orWhere('name', 'like', '%'.$search_string.'%');
                });
            }

            //count resources
            $count_resources = $resources->count();

            //add max resources filter
            $resources->take($max_resources);

            if ($page != 1)
            {
                $skip = ($page - 1) * $max_resources;

                $resources->skip($skip);

                $paginate = 'T';

                if (($skip + $max_resources) < $count_resources)
                {
                    $previous_pagination = 'T';
                    $next_pagination = 'T';
                }
                else
                {
                    $previous_pagination = 'T';
                    $next_pagination = 'F';
                }
            }
            else
            {
                if ($count_resources > $max_resources)
                {
                    $paginate = 'T';
                    $previous_pagination = 'F';
                    $next_pagination = 'T';
                }
                else
                {
                    $paginate = 'F';
                    $previous_pagination = 'F';
                    $next_pagination = 'F';
                }
            }

            $resources = $resources->orderBy('id')->get();

            foreach ($resources as $resource)
            {
                //set single resource array
                $single_resource_array = ['id' => $resource->id, 'code' => $resource->code, 'name' => $resource->name,
                    'picture' => $picture_path.$resource->picture,
                    'route' => route($resource_models_array[$resource_type]['route'], $resource->id)];

                //machines, tools, equipment and vehicles
                if ($resource_type == 1 || $resource_type == 2 || $resource_type == 3 || $resource_type == 4)
                {
                    //add resource data to single resource array
                    $single_resource_array['manufacturer'] = $resource->manufacturer->name;
                    $single_resource_array['model'] = $resource->model;
                }
                //employees
                else
                {
                    //add resource data to single resource array
                    $single_resource_array['work_type'] = $resource->workType->name;
                    $single_resource_array['oib'] = $resource->oib;

                    //if employee is head of site get additional employee sites
                    if ($resource->work_type_id == 43)
                    {
                        //get additional employee sites
                        $additional_sites = AdditionalManipulation::select('site_id')->where('resource_type', '=', 5)
                            ->where('resource_id', '=', $resource->id)->whereNull('end_time')->get();

                        //add additional sites to single resource array
                        $single_resource_array['additional_sites'] = $additional_sites;
                    }
                }

                //add single resource array to resources array
                $resources_array[] = $single_resource_array;
            }

            if ($list_type == 'active')
            {
                //get active sites
                $active_sites = $this->getActiveSites('F');
                $sites = $active_sites['data'];

                //get active parking
                $active_parking = $this->getActiveParking('F');
                $parking = $active_parking['data'];
            }
            else
            {
                //get active sites with excluded selected site
                $active_sites = $this->getActiveSites('F', $site_id);
                $sites = $active_sites['data'];

                //get active parking with excluded selected parking
                $active_parking = $this->getActiveParking('F', $parking_id);
                $parking = $active_parking['data'];
            }

            //set status types array
            $status_types_array = [['id' => 1, 'name' => 'Godišnji'], ['id' => 2, 'name' => 'Bolovanje'],
                ['id' => 3, 'name' => 'Slobodni dani']];

            return ['status' => 1, 'resources' => $resources_array, 'table_header' => $header, 'paginate' => $paginate,
                'previous_pagination' => $previous_pagination, 'next_pagination' => $next_pagination, 'page' => $page,
                'sites' => $sites, 'parking' => $parking, 'status_types' => $status_types_array,
                'filter_options' => $filter_options, 'resources_counter' => $count_resources];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get resource location
    public function getResourceLocation($location_type, $resource_type, $resource_id, $site_id = false)
    {
        if ($location_type == 'status')
        {
            $current_status = EmployeeManipulation::select('type_id')->where('employee_id', '=', $resource_id)->whereNull('end_time')->first();

            if ($current_status)
            {
                return $current_status->type_id;
            }
        }
        elseif ($location_type == 'site')
        {
            $current_site = SiteManipulation::select('site_id')->where('resource_type', '=', $resource_type)
                ->where('resource_id', '=', $resource_id)->whereNull('end_time')->first();

            if ($current_site)
            {
                return $current_site->site_id;
            }
        }
        elseif ($location_type == 'parking')
        {
            $current_parking = ParkingManipulation::select('parking_id')->where('resource_type', '=', $resource_type)
                ->where('resource_id', '=', $resource_id)->whereNull('end_time')->first();

            if ($current_parking)
            {
                return $current_parking->parking_id;
            }
        }
        else
        {
            $current_site = SiteManipulation::select('site_id')->where('site_id', '=', $site_id)->where('resource_type', '=', $resource_type)
                ->where('resource_id', '=', $resource_id)->whereNull('end_time')->first();

            if ($current_site)
            {
                return $current_site->site_id;
            }

            $current_site = AdditionalManipulation::select('site_id')->where('site_id', '=', $site_id)
                ->where('resource_type', '=', $resource_type)->where('resource_id', '=', $resource_id)->whereNull('end_time')->first();

            if ($current_site)
            {
                return $current_site->site_id;
            }
        }

        return null;
    }

    //remove resource from previous location
    private function removeResourceFromPreviousLocation($manipulation_type, $resource_type, $resource_id, $site_id = false)
    {
        try
        {
            //get current datetime
            $current_datetime = date('Y-m-d H:i:s');

            if ($manipulation_type != 'additional')
            {
                //update end time on previous site manipulations
                SiteManipulation::where('resource_type', '=', $resource_type)->where('resource_id', '=', $resource_id)->whereNull('end_time')
                    ->update(['end_time' => $current_datetime]);

                //update end time on previous parking manipulations
                ParkingManipulation::where('resource_type', '=', $resource_type)->where('resource_id', '=', $resource_id)->whereNull('end_time')
                    ->update(['end_time' => $current_datetime]);

                if ($resource_type == 5)
                {
                    //update end time on previous employee manipulations
                    EmployeeManipulation::where('employee_id', '=', $resource_id)->whereNull('end_time')
                        ->update(['end_time' => $current_datetime]);

                    if ($manipulation_type == 'site')
                    {
                        //update end time on explicit additional site manipulation
                        AdditionalManipulation::where('site_id', '=', $site_id)->where('resource_type', '=', $resource_type)
                            ->where('resource_id', '=', $resource_id)->whereNull('end_time')->update(['end_time' => $current_datetime]);
                    }
                    else
                    {
                        //update end time on all previous additional site manipulations
                        AdditionalManipulation::where('resource_type', '=', $resource_type)->where('resource_id', '=', $resource_id)
                            ->whereNull('end_time')->update(['end_time' => $current_datetime]);
                    }
                }
            }

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //do manipulation
    public function doManipulation($manipulation_type, $location_id, $resource_type, $resource_id)
    {
        try
        {
            //set resources array
            $resources_array = [
                1 => trans('main.machine'), 2 => trans('main.tool'), 3 => trans('main.equipment'), 4 => trans('main.vehicle'),
                5 => trans('main.employee')];

            //get current datetime
            $current_datetime = date('Y-m-d H:i:s');

            //start transaction
            DB::beginTransaction();

            //call removeResourceFromPreviousLocation method to remove resource from previous location
            $response = $this->removeResourceFromPreviousLocation($manipulation_type, $resource_type, $resource_id, $location_id);

            //if response status = 0 return error message
            if ($response['status'] == 0)
            {
                return ['status' => 0];
            }

            //call getUserEmployeeId method from EmployeeRepository to get manipulator id
            $repo = new EmployeeRepository;
            $manipulator_id = $repo->getUserEmployeeId();

            if ($manipulation_type == 'status')
            {
                //insert new manipulation
                $manipulation = new EmployeeManipulation;
                $manipulation->type_id = $location_id;
                $manipulation->manipulator_id = $manipulator_id;
                $manipulation->employee_id = $resource_id;
                $manipulation->start_time = $current_datetime;
                $manipulation->save();

                //set status names array
                $status_names_array = ['godišnji odmor', 'bolovanje', 'slobodne dane'];

                //set manipulation message
                $message = trans('main.employee_status_changed', ['status' => $status_names_array[$location_id - 1]]);
            }
            elseif ($manipulation_type == 'site')
            {
                //insert new manipulation
                $manipulation = new SiteManipulation;
                $manipulation->site_id = $location_id;
                $manipulation->manipulator_id = $manipulator_id;
                $manipulation->resource_type = $resource_type;
                $manipulation->resource_id = $resource_id;
                $manipulation->start_time = $current_datetime;
                $manipulation->save();

                //set manipulation message
                $message = $resources_array[$resource_type].' '.trans_choice('main.resource_moved_to_site', $resource_type);
            }
            elseif ($manipulation_type == 'parking')
            {
                //insert new manipulation
                $manipulation = new ParkingManipulation;
                $manipulation->parking_id = $location_id;
                $manipulation->manipulator_id = $manipulator_id;
                $manipulation->resource_type = $resource_type;
                $manipulation->resource_id = $resource_id;
                $manipulation->start_time = $current_datetime;
                $manipulation->save();

                //set manipulation message
                $message = $resources_array[$resource_type].' '.trans_choice('main.resource_moved_to_parking', $resource_type);
            }
            else
            {
                //insert new manipulation
                $manipulation = new AdditionalManipulation;
                $manipulation->site_id = $location_id;
                $manipulation->manipulator_id = $manipulator_id;
                $manipulation->resource_type = $resource_type;
                $manipulation->resource_id = $resource_id;
                $manipulation->start_time = $current_datetime;
                $manipulation->save();

                //set manipulation message
                $message = trans('main.employee_added_to_additional_site');
            }

            //commit transaction
            DB::commit();

            return ['status' => 1, 'message' => $message];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //remove employee from additional site
    public function removeAdditionalSite($site_id, $employee_id)
    {
        try
        {
            //get current datetime
            $current_datetime = date('Y-m-d H:i:s');

            $manipulation = AdditionalManipulation::where('site_id', '=', $site_id)->where('resource_type', '=', 5)
                ->where('resource_id', '=', $employee_id)->whereNull('end_time')->first();
            $manipulation->end_time = $current_datetime;
            $manipulation->save();

            //set manipulation message
            $message = trans('main.employee_removed_from_additional_site');

            return ['status' => 1, 'message' => $message];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get current user site
    public function getCurrentUserSite($user)
    {
        //set additional sites array
        $additional_sites_array = [];

        //get employee id of current user
        $employee = Employee::select('id')->where('user_id', '=', $user->id)->first();
        $employee_id = $employee->id;

        //get site id of current user
        $site = SiteManipulation::select('site_id')->where('resource_type', '=', 5)->where('resource_id', '=', $employee_id)
            ->whereNull('end_time')->first();

        if (!$site)
        {
            return ['status' => 2];
        }

        //if user has 'HeadOfSite' role get additional sites
        if ($user->hasRole('HeadOfSite'))
        {
            $additional_sites = AdditionalManipulation::select('site_id')->where('resource_type', '=', 5)
                ->where('resource_id', '=', $employee_id)->whereNull('end_time')->get();

            foreach ($additional_sites as $additional_site)
            {
                //add site to additional sites array
                $additional_sites_array[] = $additional_site->site_id;
            }
        }

        return ['status' => 1, 'site_id' => $site->site_id, 'additional_sites' => $additional_sites_array];
    }

    //get current site employees who can use daily work activity
    public function getCurrentSiteDWAEmployees($site_id, $dwa_date = false)
    {
        //set employees array
        $employees_array = [];

        $employees = Employee::select('id', 'name')
            ->whereIn('work_type_id', [29, 30, 31, 32, 33, 34, 35, 36])
            ->whereIn('id', function($query) use ($site_id, $dwa_date) {
                $query->select('resource_id')
                    ->from('site_manipulations')
                    ->where('site_id', '=', $site_id)
                    ->where('resource_type', '=', 5);

                if ($dwa_date)
                {
                    $query->where(function($query2) use ($dwa_date) {
                        $query2->whereRaw('? >= DATE(start_time) AND ? <= DATE(end_time)', [$dwa_date, $dwa_date])
                        ->orWhereRaw('? >= DATE(start_time) AND end_time IS NULL', [$dwa_date]);
                    });
                }
                else
                {
                    $query->whereNull('end_time');
                }
            });

        $employees = $employees->get();

        foreach ($employees as $employee)
        {
            //add employee to employees array
            $employees_array[$employee->id] = $employee->name;
        }

        return $employees_array;
    }

    //get current site resources
    public function getCurrentSiteResources($site_id, $resource_type, $dwa_date = false)
    {
        //set resources models array
        $resources_models_array = [1 => ['model' => Machine::select('id', 'name'), 'default_option' => trans('main.choose_machine')],
            2 => ['model' => Tool::select('id', 'name'), 'default_option' => trans('main.choose_tool')]];

        //set resources array
        $resources_array = [];

        //add default option to resources array
        $resources_array[0] = $resources_models_array[$resource_type]['default_option'];

        $resources = $resources_models_array[$resource_type]['model']
            ->whereIn('id', function($query) use ($site_id, $resource_type, $dwa_date) {
            $query->select('resource_id')
                ->from('site_manipulations')
                ->where('site_id', '=', $site_id)
                ->where('resource_type', '=', $resource_type);

                if ($dwa_date)
                {
                    $query->where(function($query2) use ($dwa_date) {
                        $query2->whereRaw('? >= DATE(start_time) AND ? <= DATE(end_time)', [$dwa_date, $dwa_date])
                            ->orWhereRaw('? >= DATE(start_time) AND end_time IS NULL', [$dwa_date]);
                    });
                }
                else
                {
                    $query->whereNull('end_time');
                }
            });

        $resources = $resources->get();

        foreach ($resources as $resource)
        {
            //add resource to resources array
            $resources_array[$resource->id] = $resource->name;
        }

        return $resources_array;
    }

    //get head of site sites and resources
    public function getHeadOfSiteSitesAndResources($site_id, $additional_sites)
    {
        //set sites array
        $sites_array = [];

        //add main site id on the beginning of additional sites array
        array_unshift($additional_sites, $site_id);

        //add default option to sites array
        $sites_array[] = ['id' => 0, 'name' => trans('main.choose_site')];

        foreach ($additional_sites as $additional_site)
        {
            //call getCurrentSiteDWAEmployees method to get employees who can use daily work activity
            $employees = $this->getCurrentSiteDWAEmployees($additional_site);

            //call getCurrentSiteResources method to get current site resources
            $machines = $this->getCurrentSiteResources($additional_site, 1);

            //call getCurrentSiteResources method to get current site resources
            $tools = $this->getCurrentSiteResources($additional_site, 2);

            //get site name
            $site_name = Site::find($additional_site)->name;

            //add employees and machines to sites array
            $sites_array[] = ['id' => $additional_site, 'name' => $site_name, 'employees' => $employees, 'machines' => $machines,
                'tools' => $tools];
        }

        return $sites_array;
    }

    //check current site resource
    public function checkCurrentSiteResource($site_id, $resource_type, $resource_id)
    {
        $resource = SiteManipulation::where('site_id', '=', $site_id)->where('resource_type', '=', $resource_type)
            ->where('resource_id', '=', $resource_id)->whereNull('end_time')->first();

        return $resource;
    }

    //get current user history site
    public function getCurrentUserHistorySite($user, $dwa_site_id, $dwa_date)
    {
        //get employee id of current user
        $employee = Employee::select('id')->where('user_id', '=', $user->id)->first();
        $employee_id = $employee->id;

        //get site id of current user
        $site = SiteManipulation::select('site_id')->where('resource_type', '=', 5)->where('resource_id', '=', $employee_id)
            ->where('site_id', '=', $dwa_site_id)->where(function($query) use ($dwa_date) {
                $query->whereRaw('? >= DATE(start_time) AND ? <= DATE(end_time)', [$dwa_date, $dwa_date])
                    ->orWhereRaw('? >= DATE(start_time) AND end_time IS NULL', [$dwa_date]);
            })->first();

        if (!$site)
        {
            //get additional site
            if ($user->hasRole('HeadOfSite'))
            {
                $site = AdditionalManipulation::select('site_id')->where('resource_type', '=', 5)
                    ->where('resource_id', '=', $employee_id)->where(function($query) use ($dwa_date) {
                        $query->whereRaw('? >= DATE(start_time) AND ? <= DATE(end_time)', [$dwa_date, $dwa_date])
                            ->orWhereRaw('? >= DATE(start_time) AND end_time IS NULL', [$dwa_date]);
                    })->first();
            }
        }

        if ($site)
        {
            return $site->site_id;
        }

        return null;
    }

    //check history site resource
    public function checkHistorySiteResource($site_id, $resource_type, $resource_id, $dwa_id)
    {
        //get dwa date
        $dwa_date = DWA::find($dwa_id)->activity_date;

        $resource = SiteManipulation::where('site_id', '=', $site_id)->where('resource_type', '=', $resource_type)
            ->where('resource_id', '=', $resource_id)->where(function($query) use ($dwa_date) {
                $query->whereRaw('? >= DATE(start_time) AND ? <= DATE(end_time)', [$dwa_date, $dwa_date])
                    ->orWhereRaw('? >= DATE(start_time) AND end_time IS NULL', [$dwa_date]);
            })->first();

        return $resource;
    }

    //resources overview
    public function resourcesOverview($resource_type, $search_filter, $search_string)
    {
        try
        {
            //set resources array
            $resources_array = [];

            //set resources models array
            $resource_models_array = [
                1 => ['model' => Machine::with('manufacturer')->select('id', 'code', 'manufacturer_id', 'name', 'model', 'picture'),
                    'name' => 'machines', 'route' => 'EditMachine'],
                2 => ['model' => Tool::with('manufacturer')->select('id', 'code', 'manufacturer_id', 'name', 'model', 'picture'),
                    'name' => 'tools', 'route' => 'EditTool'],
                3 => ['model' => Equipment::with('manufacturer')->select('id', 'code', 'manufacturer_id', 'name', 'model', 'picture'),
                    'name' => 'equipment', 'route' => 'EditEquipment'],
                4 => ['model' => Vehicle::with('manufacturer')->select('id', 'code', 'manufacturer_id', 'name', 'model', 'picture'),
                    'name' => 'vehicles', 'route' => 'EditVehicle'],
                5 => ['model' => Employee::with('workType')->select('id', 'code', 'work_type_id', 'name', 'oib', 'picture'),
                    'name' => 'employees', 'route' => 'EditEmployee']];

            //set table headers array
            $headers_array = [
                1 => [trans('main.code'), trans('main.manufacturer'), trans('main.name'), trans('main.model'),
                    trans('main.site'), trans('main.parking_single')],
                5 => [trans('main.employee_code'), trans('main.employee_name'), trans('main.work_type'),
                    trans('main.oib'), trans('main.site'), trans('main.status')]];

            //set table headers
            if ($resource_type != 5)
            {
                $header = $headers_array[1];
            }
            else
            {
                $header = $headers_array[$resource_type];
            }

            //get resource model
            $resources = $resource_models_array[$resource_type]['model'];

            //add where condition for active status
            $resources->where('status_id', '=', 1);

            //if resource type = '5' exclude first admin
            if ($resource_type == 5)
            {
                $resources->where('id', '!=', 1);
            }

            if ($search_filter != 0)
            {
                //set filter columns array
                $filter_columns = [1 => 'machine_type_id', 2 => 'tool_type_id', 3 => 'equipment_type_id', 4 => 'vehicle_type_id',
                    5 => 'work_type_id'];

                $resources->where($filter_columns[$resource_type], '=', $search_filter);
            }

            if ($search_string != null)
            {
                $resources->where(function($query) use ($search_string) {
                    $query->where('code', 'like', '%'.$search_string.'%')
                        ->orWhere('name', 'like', '%'.$search_string.'%');
                });
            }

            $resources = $resources->get();

            foreach ($resources as $resource)
            {
                $current_site_name = '';
                $current_parking_name = '';
                $additional_sites = [];
                $current_status = '';

                //get resource current site id
                $current_site = SiteManipulation::with('site')
                    ->select('site_id')->where('resource_type', '=', $resource_type)->where('resource_id', '=', $resource->id)
                    ->whereNull('end_time')->first();

                //get resource current parking id
                $current_parking = ParkingManipulation::with('parking')
                    ->select('parking_id')->where('resource_type', '=', $resource_type)->where('resource_id', '=', $resource->id)
                    ->whereNull('end_time')->first();

                if ($current_site)
                {
                    //set resource current site
                    $current_site_name = $current_site->site->name;

                    if ($resource_type == 5 && $resource->work_type_id == 43)
                    {
                        $additional_sites = AdditionalManipulation::with('siteName')
                            ->select('site_id')->where('resource_type', '=', 5)->where('resource_id', '=', $resource->id)->whereNull('end_time')
                            ->get();

                        foreach ($additional_sites as $additional_site)
                        {
                            $current_site_name .= ', '.$additional_site->siteName->name;
                        }
                    }
                }
                elseif ($current_parking)
                {
                    //set resource current parking
                    $current_parking_name = $current_parking->parking->name;
                }
                elseif ($resource_type == 5)
                {
                    //get employee current status
                    $current_status = EmployeeManipulation::select('type_id')->where('employee_id', '=', $resource->id)->whereNull('end_time')
                        ->first();

                    if ($current_status)
                    {
                        $status = $current_status->type_id;

                        if ($status == 1)
                        {
                            $status_name = 'Godišnji odmor';
                        }
                        elseif ($status == 2)
                        {
                            $status_name = 'Bolovanje';
                        }
                        else
                        {
                            $status_name = 'Slobodni dani';
                        }

                        //set employee current status
                        $current_status = $status_name;
                    }
                }

                //set single resource array
                $single_resource_array = ['id' => $resource->id, 'code' => $resource->code, 'name' => $resource->name,
                    'current_site' => $current_site_name, 'additional_sites' => $additional_sites, 'current_parking' => $current_parking_name,
                    'current_status' => $current_status, 'route' => $resource_models_array[$resource_type]['route']];

                //machines, tools, equipment and vehicles
                if ($resource_type == 1 || $resource_type == 2 || $resource_type == 3 || $resource_type == 4)
                {
                    //add resource data to single resource array
                    $single_resource_array['manufacturer'] = $resource->manufacturer->name;
                    $single_resource_array['model'] = $resource->model;
                }
                //employees
                else
                {
                    //add resource data to single resource array
                    $single_resource_array['work_type'] = $resource->workType->name;
                    $single_resource_array['oib'] = $resource->oib;
                }

                //add single resource array to resources array
                $resources_array[] = $single_resource_array;
            }

            //set page title
            $page_title = trans('main.'.$resource_models_array[$resource_type]['name']);

            return ['status' => 1, 'resources' => $resources_array, 'table_header' => $header, 'page_title' => $page_title];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }
}
