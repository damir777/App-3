<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Repositories\ManipulationRepository;
use App\Repositories\GeneralRepository;

class DashboardController extends Controller
{
    //set repo variable
    protected $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new ManipulationRepository;
    }

    //get dashboard page
    public function getDashboard()
    {
        return view('dashboard');
    }

    //resources overview
    public function resourcesOverview($type, Request $request)
    {
        //get search parameters
        $search_string = $request->search_string;
        $search_filter = $request->search_filter;

        //call getGeneralTypesSelect method from GeneralRepository to get resource types - select
        $this->repo = new GeneralRepository;
        $resource_types = $this->repo->getGeneralTypesSelect($type, 1);

        //call resourcesOverview method from ManipulationRepository to get resources overview
        $this->repo = new ManipulationRepository;
        $resources = $this->repo->resourcesOverview($type, $search_filter, $search_string);

        //if response status = 0 return error message
        if ($resource_types ['status'] == 0 || $resources['status'] == 0)
        {
            return view('errors.500');
        }

        return view('overview.list', ['type' => $type, 'search_filter' => $search_filter, 'search_string' => $search_string,
            'resource_types' => $resource_types['data'], 'page_title' => $resources['page_title'], 'table_header' => $resources['table_header'],
            'resources' => $resources['resources']]);
    }

    //resources pdf
    public function resourcesPdf($type, Request $request)
    {
        //get search parameters
        $search_string = $request->search_string;
        $search_filter = $request->search_filter;

        //call resourcesOverview method from ManipulationRepository to get resources overview
        $this->repo = new ManipulationRepository;
        $resources = $this->repo->resourcesOverview($type, $search_filter, $search_string);

        //if response status = 0 return error message
        if ($resources['status'] == 0)
        {
            return view('errors.500');
        }

        $pdf = PDF::loadView('overview.pdf', ['type' => $type, 'page_title' => $resources['page_title'],
            'table_header' => $resources['table_header'], 'resources' => $resources['resources']])->setPaper('a4', 'landscape');
        return $pdf->download($resources['page_title'].'.pdf');
    }
}
