<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\Auth;
use App\GeneralType;
use App\Status;
use App\City;
use App\Country;
use App\Role;

class GeneralRepository
{
    //get user home page route
    public function getUserHomePageRoute()
    {
        //set default route name
        $route = 'DashboardPage';

        //get user
        $user = Auth::user();

        if ($user->hasRole('Employee') ||$user->hasRole('Mechanic'))
        {
            $route = 'GetDWA';
        }

        return $route;
    }

    //insert general type
    public function insertGeneralType($type, $name)
    {
        try
        {
            $general_type = new GeneralType;
            $general_type->type = $type;
            $general_type->name = $name;
            $general_type->save();

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get general types - select
    public function getGeneralTypesSelect($type, $all_types = false)
    {
        try
        {
            //set all types translations array
            $all_types_translations = [1 => trans('main.all_machines'), 2 => trans('main.all_tools'), 3 => trans('main.all_equipment'),
                4 => trans('main.all_vehicles'), 5 => trans('main.all_employees'), 9 => trans('main.choose_activity')];

            //get types
            $types = GeneralType::select('id', 'name')->where('type', '=', $type)->orderBy('name', 'asc')->get();

            //set types array
            $types_array = array();

            if ($all_types)
            {
                //add add default option to types array
                $types_array[0] = $all_types_translations[$type];
            }

            //loop through all types
            foreach ($types as $type)
            {
                //add type to types array
                $types_array[$type->id] = $type->name;
            }

            return ['status' => 1, 'data' => $types_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get statuses - select
    public function getStatusesSelect()
    {
        try
        {
            //get statuses
            $statuses = Status::select('id', 'name')->get();

            //set statuses array
            $statuses_array = array();

            //loop through all statuses
            foreach ($statuses as $status)
            {
                //add type to statuses array
                $statuses_array[$status->id] = $status->name;
            }

            return ['status' => 1, 'data' => $statuses_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get cities - select
    public function getCitiesSelect()
    {
        try
        {
            //get cities
            $cities = City::select('id', 'name')->get();

            //set cities array
            $cities_array = array();

            //loop through all cities
            foreach ($cities as $city)
            {
                //add city to cities array
                $cities_array[$city->id] = $city->name;
            }

            return ['status' => 1, 'data' => $cities_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get countries - select
    public function getCountriesSelect()
    {
        try
        {
            //get countries
            $countries = Country::select('id', 'name')->get();

            //set countries array
            $countries_array = array();

            //loop through all countries
            foreach ($countries as $country)
            {
                //add country to countries array
                $countries_array[$country->id] = $country->name;
            }

            return ['status' => 1, 'data' => $countries_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get user roles - select
    public function getRolesSelect()
    {
        try
        {
            //get roles
            $roles = Role::select('id', 'display_name')->get();

            //set roles array
            $roles_array = array();

            //loop through all roles
            foreach ($roles as $role)
            {
                //add country to roles array
                $roles_array[$role->id] = $role->display_name;
            }

            return ['status' => 1, 'data' => $roles_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }
}
