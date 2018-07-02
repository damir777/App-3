<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Site;
use App\City;

class SiteRepository
{
    //get sites
    public function getSites()
    {
        try
        {
            $sites = Site::with('country', 'investor', 'status')
                ->select('id', 'name', 'country_id', 'city', 'investor_id', 'status_id')->paginate(30);

            return ['status' => 1, 'data' => $sites];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //insert site
    public function insertSite($code, $name, $country, $city_id, $city, $address, $investor, $start_date, $plan_end_date, $end_date,
        $project_manager, $status, $notes, $latitude, $longitude)
    {
        try
        {
            //format start date
            $start_date = date('Y-m-d', strtotime($start_date));

            //format plan end date
            $plan_end_date = date('Y-m-d', strtotime($plan_end_date));

            if ($end_date)
            {
                //format end date
                $end_date = date('Y-m-d', strtotime($end_date));
            }

            if ($country == 1)
            {
                //set city
                $city = City::find($city_id)->name;
            }

            //start transaction
            DB::beginTransaction();

            $site = new Site;
            $site->code = $code;
            $site->name = $name;
            $site->country_id = $country;
            $site->city_id = $city_id;
            $site->city = $city;
            $site->address = $address;
            $site->investor_id = $investor;
            $site->start_date = $start_date;
            $site->plan_end_date = $plan_end_date;

            if ($end_date)
            {
                $site->end_date = $end_date;
            }

            $site->project_manager_id = $project_manager;
            $site->status_id = $status;
            $site->notes = $notes;
            $site->latitude = $latitude;
            $site->longitude = $longitude;
            $site->save();

            //commit transaction
            DB::commit();

            //set insert site flash
            Session::flash('success_message', trans('main.site_insert'));

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get site details
    public function getSiteDetails($id)
    {
        try
        {
            $site = Site::find($id);

            //if site doesn't exist return error message
            if (!$site)
            {
                return array('status' => 0);
            }

            //format start date
            $site->start_date = date('d.m.Y.', strtotime($site->start_date));

            //format plan end date
            $site->plan_end_date = date('d.m.Y.', strtotime($site->plan_end_date));

            if ($site->end_date)
            {
                //format end date
                $site->end_date = date('d.m.Y.', strtotime($site->end_date));
            }

            return ['status' => 1, 'data' => $site];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //update site
    public function updateSite($id, $code, $name, $country, $city_id, $city, $address, $investor, $start_date, $plan_end_date, $end_date,
        $project_manager, $status, $notes, $latitude, $longitude)
    {
        try
        {
            //format start date
            $start_date = date('Y-m-d', strtotime($start_date));

            //format plan end date
            $plan_end_date = date('Y-m-d', strtotime($plan_end_date));

            if ($end_date)
            {
                //format end date
                $end_date = date('Y-m-d', strtotime($end_date));
            }

            if ($country == 1)
            {
                //set city
                $city = City::find($city_id)->name;
            }

            //start transaction
            DB::beginTransaction();

            $site = Site::find($id);
            $site->code = $code;
            $site->name = $name;
            $site->country_id = $country;
            $site->city_id = $city_id;
            $site->city = $city;
            $site->address = $address;
            $site->investor_id = $investor;
            $site->start_date = $start_date;
            $site->plan_end_date = $plan_end_date;

            if ($end_date)
            {
                $site->end_date = $end_date;
            }

            $site->project_manager_id = $project_manager;
            $site->status_id = $status;
            $site->notes = $notes;
            $site->latitude = $latitude;
            $site->longitude = $longitude;
            $site->save();

            //commit transaction
            DB::commit();

            //set update site flash
            Session::flash('success_message', trans('main.site_update'));

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get sites - select
    public function getSitesSelect()
    {
        try
        {
            //get sites
            $sites = Site::select('id', 'name')->get();

            //set sites array
            $sites_array = array();

            //add default option to sites array
            $sites_array[0] = trans('main.choose_site');

            //loop through all sites
            foreach ($sites as $site)
            {
                //add site to sites array
                $sites_array[$site->id] = $site->name;
            }

            return ['status' => 1, 'data' => $sites_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }
}
