<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CustomValidator\Validator as CustomValidator;
use App\Employee;
use App\Repositories\EmployeeRepository;
use App\Repositories\GeneralRepository;

class EmployeesController extends Controller
{
    //set repo variable
    protected $repo;

    public function __construct()
    {
        //set repo
        $this->repo = new EmployeeRepository;
    }

    //get employees
    public function getEmployees(Request $request)
    {
        //get search parameters
        $search_string = $request->search_string;
        $work_type = $request->work_type;

        //call getGeneralTypesSelect method from GeneralRepository to get work types - select
        $this->repo = new GeneralRepository;
        $work_types = $this->repo->getGeneralTypesSelect(5, 1);

        //call getEmployees method from EmployeeRepository to get employees
        $this->repo = new EmployeeRepository;
        $employees = $this->repo->getEmployees($search_string, $work_type);

        //if response status = 0 return error message
        if ($work_types['status'] == 0 || $employees['status'] == 0)
        {
            return view('errors.500');
        }

        return view('employees.list', ['work_types' => $work_types['data'], 'employees' => $employees['data'], 'search_string' => $search_string,
            'work_type' => $work_type]);
    }

    //add employee
    public function addEmployee()
    {
        //call getGeneralTypesSelect method from GeneralRepository to get work types - select
        $this->repo = new GeneralRepository;
        $work_types = $this->repo->getGeneralTypesSelect(5);

        //call getGeneralTypesSelect method from GeneralRepository to get contract types - select
        $contract_types = $this->repo->getGeneralTypesSelect(7);

        //call getCitiesSelect method from GeneralRepository to get cities - select
        $cities = $this->repo->getCitiesSelect();

        //call getCountriesSelect method from GeneralRepository to get countries - select
        $countries = $this->repo->getCountriesSelect();

        //call getStatusesSelect method from GeneralRepository to get statuses - select
        $statuses = $this->repo->getStatusesSelect();

        //call getRolesSelect method from GeneralRepository to get roles - select
        $roles = $this->repo->getRolesSelect();

        //if response status = 0 return error message
        if ($work_types['status'] == 0 || $contract_types['status'] == 0 || $cities['status'] == 0 || $countries['status'] == 0 ||
            $statuses['status'] == 0 || $roles['status'] == 0)
        {
            return view('errors.500');
        }

        return view('employees.addEmployee', ['work_types' => $work_types['data'], 'contract_types' => $contract_types['data'],
            'cities' => $cities['data'], 'countries' => $countries['data'], 'statuses' => $statuses['data'],
            'roles' => $roles['data']]);
    }

    //insert employee - ajax
    public function insertEmployee(Request $request)
    {
        $code = $request->code;
        $name = $request->name;
        $work_type = $request->work_type;
        $contract_type = $request->contract_type;
        $picture = $request->picture;
        $sex = $request->sex;
        $oib = $request->oib;
        $birth_date = $request->birth_date;
        $citizenship = $request->citizenship;
        $birth_city = $request->birth_city;
        $country = $request->country;
        $city_id = $request->city_id;
        $city = $request->city;
        $address = $request->address;
        $phone = $request->phone;
        $contract_start_date = $request->contract_start_date;
        $contract_expire_date = $request->contract_expire_date;
        $medical_certificate_expire_date = $request->medical_certificate_expire_date;
        $contract_end_date = $request->contract_end_date;
        $status = $request->status;
        $create_account = $request->create_account;
        $role = $request->user_role;
        $email = $request->email;
        $password = $request->password;

        //validate form inputs
        $validator = Validator::make($request->all(), Employee::validateEmployeeForm());

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 2]);
        }

        //validate employee code, oib, email and password
        $validator = CustomValidator::validate('employee', ['code', 'oib', 'email', 'password']);

        //if form input is not correct return error message
        if (!$validator['status'])
        {
            return response()->json(['status' => 3, 'error' => $validator['error']]);
        }

        //call insertEmployee method from EmployeeRepository to insert employee
        $response = $this->repo->insertEmployee($code, $name, $work_type, $contract_type, $picture, $sex, $oib, $birth_date,
            $citizenship, $birth_city, $country, $city_id, $city, $address, $phone, $contract_start_date, $contract_expire_date,
            $medical_certificate_expire_date, $contract_end_date, $status, $create_account, $role, $email, $password);

        return response()->json($response);
    }

    //edit employee
    public function editEmployee($id)
    {
        //call getGeneralTypesSelect method from GeneralRepository to get work types - select
        $this->repo = new GeneralRepository;
        $work_types = $this->repo->getGeneralTypesSelect(5);

        //call getGeneralTypesSelect method from GeneralRepository to get contract types - select
        $contract_types = $this->repo->getGeneralTypesSelect(7);

        //call getCitiesSelect method from GeneralRepository to get cities - select
        $cities = $this->repo->getCitiesSelect();

        //call getCountriesSelect method from GeneralRepository to get countries - select
        $countries = $this->repo->getCountriesSelect();

        //call getStatusesSelect method from GeneralRepository to get statuses - select
        $statuses = $this->repo->getStatusesSelect();

        //call getRolesSelect method from GeneralRepository to get roles - select
        $roles = $this->repo->getRolesSelect();

        //call getEmployeeDetails method from EmployeeRepository to get employee details
        $this->repo = new EmployeeRepository;
        $employee = $this->repo->getEmployeeDetails($id);

        //if response status = 0 return error message
        if ($work_types['status'] == 0 || $contract_types['status'] == 0 || $cities['status'] == 0 || $countries['status'] == 0 ||
            $statuses['status'] == 0 || $roles['status'] == 0 || $employee['status'] == 0)
        {
            return redirect()->route('GetEmployees')->with('error_message', trans('errors.error'));
        }

        return view('employees.editEmployee', ['work_types' => $work_types['data'], 'contract_types' => $contract_types['data'],
            'cities' => $cities['data'], 'countries' => $countries['data'], 'statuses' => $statuses['data'],
            'roles' => $roles['data'], 'employee' => $employee['data']]);
    }

    //update employee - ajax
    public function updateEmployee(Request $request)
    {
        $id = $request->id;
        $code = $request->code;
        $name = $request->name;
        $work_type = $request->work_type;
        $contract_type = $request->contract_type;
        $picture = $request->picture;
        $sex = $request->sex;
        $oib = $request->oib;
        $birth_date = $request->birth_date;
        $citizenship = $request->citizenship;
        $birth_city = $request->birth_city;
        $country = $request->country;
        $city_id = $request->city_id;
        $city = $request->city;
        $address = $request->address;
        $phone = $request->phone;
        $contract_start_date = $request->contract_start_date;
        $contract_expire_date = $request->contract_expire_date;
        $medical_certificate_expire_date = $request->medical_certificate_expire_date;
        $contract_end_date = $request->contract_end_date;
        $status = $request->status;
        $create_account = $request->create_account;
        $role = $request->user_role;
        $email = $request->email;
        $password = $request->password;

        //set default new picture variable
        $new_picture = 'F';

        //check if new picture exists
        if ($request->hasFile('picture'))
        {
            $new_picture = 'T';
        }

        //validate form inputs
        $validator = Validator::make($request->all(), Employee::validateEmployeeForm($id));

        //if form input is not correct return error message
        if (!$validator->passes())
        {
            return response()->json(['status' => 2]);
        }

        //validate employee code, oib, email and password
        $validator = CustomValidator::validate('employee', ['code', 'oib', 'email', 'password'], $id);

        //if form input is not correct return error message
        if (!$validator['status'])
        {
            return response()->json(['status' => 3, 'error' => $validator['error']]);
        }

        //call updateEmployee method from EmployeeRepository to update employee
        $response = $this->repo->updateEmployee($id, $code, $name, $work_type, $contract_type, $picture, $sex, $oib, $birth_date,
            $citizenship, $birth_city, $country, $city_id, $city, $address, $phone, $contract_start_date, $contract_expire_date,
            $medical_certificate_expire_date, $contract_end_date, $status, $create_account, $role, $email, $password, $new_picture);

        return response()->json($response);
    }
}
