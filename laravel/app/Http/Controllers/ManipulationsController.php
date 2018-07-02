<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomValidator\Validator as CustomValidator;
use App\Repositories\ManipulationRepository;

class ManipulationsController extends Controller
{
    //set repo variable
    protected $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new ManipulationRepository;
    }

    //get dashboard data - ajax
    public function getDashboardData(Request $request)
    {
        $get_sites = $request->get_sites;
        $get_resources = $request->get_resources;
        $employee_type = $request->employee_type;
        $resource_type = $request->resource_type;
        $list_type = $request->list_type;
        $page = $request->page;
        $search_string = $request->search_string;
        $search_filter = $request->search_filter;
        $site_id = $request->site_id;
        $parking_id = $request->parking_id;

        //call getDashboardData method from ManipulationRepository to get dashboard data
        $response = $this->repo->getDashboardData($get_sites, $get_resources, $employee_type, $resource_type, $list_type, $page, $search_string,
            $search_filter, $site_id, $parking_id);

        return response()->json($response);
    }

    //do manipulation - ajax
    public function doManipulation(Request $request)
    {
        $manipulation_type = $request->manipulation_type;
        $location_id = $request->location_id;
        $resource_type = $request->resource_type;
        $resource_id = $request->resource_id;

        //validate manipulation
        $validator = CustomValidator::validateManipulation($manipulation_type, $location_id, $resource_type, $resource_id);

        //if form input is not correct return error message
        if (!$validator['status'])
        {
            return response()->json(['status' => 2, 'error' => $validator['error']]);
        }

        //call doManipulation method from ManipulationRepository to do manipulation
        $response = $this->repo->doManipulation($manipulation_type, $location_id, $resource_type, $resource_id);

        return response()->json($response);
    }

    //remove additional site
    public function removeAdditionalSite(Request $request)
    {
        $site_id = $request->site_id;
        $employee_id = $request->employee_id;

        //validate manipulation
        $validator = CustomValidator::validateManipulation('remove_additional', $site_id, 5, $employee_id);

        //if form input is not correct return error message
        if (!$validator['status'])
        {
            return response()->json(['status' => 2, 'error' => $validator['error']]);
        }

        //call removeAdditionalSite method from ManipulationRepository to remove employee from additional site
        $response = $this->repo->removeAdditionalSite($site_id, $employee_id);

        return response()->json($response);
    }
}
