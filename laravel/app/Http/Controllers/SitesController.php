<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CustomValidator\Validator as CustomValidator;
use App\Site;
use App\Repositories\SiteRepository;
use App\Repositories\InvestorRepository;
use App\Repositories\GeneralRepository;
use App\Repositories\EmployeeRepository;

class SitesController extends Controller
{
    //set repo variable
    protected $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new SiteRepository;
    }

    //get sites
    public function getSites()
    {
        //call getSites method from SiteRepository to get sites
        $sites = $this->repo->getSites();

        //if response status = 0 return error message
        if ($sites['status'] == 0)
        {
            return view('errors.500');
        }

        return view('sites.list', ['sites' => $sites['data']]);
    }

    //add site
    public function addSite()
    {
        //call getCountriesSelect method from GeneralRepository to get countries - select
        $this->repo = new GeneralRepository;
        $countries = $this->repo->getCountriesSelect();

        //call getCitiesSelect method from GeneralRepository to get cities - select
        $cities = $this->repo->getCitiesSelect();

        //call getInvestorsSelect method from InvestorRepository to get investors - select
        $this->repo = new InvestorRepository;
        $investors = $this->repo->getInvestorsSelect();

        //call getEmployeesSelect method from EmployeeRepository to get employees (head of site) - select
        $this->repo = new EmployeeRepository;
        $employees = $this->repo->getEmployeesSelect(43);

        //call getStatusesSelect method from GeneralRepository to get statuses - select
        $this->repo = new GeneralRepository;
        $statuses = $this->repo->getStatusesSelect();

        //if response status = 0 return error message
        if ($countries['status'] == 0 || $cities['status'] == 0 || $investors['status'] == 0 || $employees['status'] == 0 ||
            $statuses['status'] == 0)
        {
            return view('errors.500');
        }

        //bind countries and cities to investor view
        view()->composer(array('modals.investor'), function($view) use ($countries, $cities) {

            $view->with('countries', $countries['data']);
            $view->with('cities', $cities['data']);
        });

        return view('sites.addSite', ['countries' => $countries['data'], 'cities' => $cities['data'], 'investors' => $investors['data'],
            'employees' => $employees['data'], 'statuses' => $statuses['data']]);
    }

    //insert site - ajax
    public function insertSite(Request $request)
    {
        $code = $request->code;
        $name = $request->name;
        $country = $request->country;
        $city_id = $request->city_id;
        $city = $request->city;
        $address = $request->address;
        $investor = $request->investor;
        $start_date = $request->start_date;
        $plan_end_date = $request->plan_end_date;
        $end_date = $request->end_date;
        $project_manager = $request->project_manager;
        $status = $request->status;
        $notes = $request->notes;
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        //validate form inputs
        $validator = Validator::make($request->all(), Site::validateSiteForm());

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 2]);
        }

        //validate site code
        $validator = CustomValidator::validate('site', ['code']);

        //if form input is not correct return error message
        if (!$validator['status'])
        {
            return response()->json(['status' => 3, 'error' => $validator['error']]);
        }

        //call insertSite method from SiteRepository to insert site
        $response = $this->repo->insertSite($code, $name, $country, $city_id, $city, $address, $investor, $start_date, $plan_end_date,
            $end_date, $project_manager, $status, $notes, $latitude, $longitude);

        return response()->json($response);
    }

    //edit site
    public function editSite($id)
    {
        //call getCountriesSelect method from GeneralRepository to get countries - select
        $this->repo = new GeneralRepository;
        $countries = $this->repo->getCountriesSelect();

        //call getCitiesSelect method from GeneralRepository to get cities - select
        $cities = $this->repo->getCitiesSelect();

        //call getInvestorsSelect method from InvestorRepository to get investors - select
        $this->repo = new InvestorRepository;
        $investors = $this->repo->getInvestorsSelect();

        //call getEmployeesSelect method from EmployeeRepository to get employees (head of site) - select
        $this->repo = new EmployeeRepository;
        $employees = $this->repo->getEmployeesSelect(43);

        //call getStatusesSelect method from GeneralRepository to get statuses - select
        $this->repo = new GeneralRepository;
        $statuses = $this->repo->getStatusesSelect();

        //call getSiteDetails method from SiteRepository to get site details
        $this->repo = new SiteRepository;
        $site = $this->repo->getSiteDetails($id);

        //if response status = 0 return error message
        if ($countries['status'] == 0 || $cities['status'] == 0 || $investors['status'] == 0 || $employees['status'] == 0 ||
            $statuses['status'] == 0 || $site['status'] == 0)
        {
            return redirect()->route('GetSites')->with('error_message', trans('errors.error'));
        }

        return view('sites.editSite', ['countries' => $countries['data'], 'cities' => $cities['data'], 'investors' => $investors['data'],
            'employees' => $employees['data'], 'statuses' => $statuses['data'], 'site' => $site['data']]);
    }

    //update site - ajax
    public function updateSite(Request $request)
    {
        $id = $request->id;
        $code = $request->code;
        $name = $request->name;
        $country = $request->country;
        $city_id = $request->city_id;
        $city = $request->city;
        $address = $request->address;
        $investor = $request->investor;
        $start_date = $request->start_date;
        $plan_end_date = $request->plan_end_date;
        $end_date = $request->end_date;
        $project_manager = $request->project_manager;
        $status = $request->status;
        $notes = $request->notes;
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        //validate form inputs
        $validator = Validator::make($request->all(), Site::validateSiteForm($id));

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 2]);
        }

        //validate site code
        $validator = CustomValidator::validate('site', ['code'], $id);

        //if form input is not correct return error message
        if (!$validator['status'])
        {
            return response()->json(['status' => 3, 'error' => $validator['error']]);
        }

        //call updateSite method from SiteRepository to update site
        $response = $this->repo->updateSite($id, $code, $name, $country, $city_id, $city, $address, $investor, $start_date, $plan_end_date,
            $end_date, $project_manager, $status, $notes, $latitude, $longitude);

        return response()->json($response);
    }
}
