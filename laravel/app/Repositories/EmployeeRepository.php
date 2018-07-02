<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as UrlRequest;
use App\Employee;
use App\City;
use App\User;
use App\RoleUser;
use App\SiteManipulation;
use App\EmployeeManipulation;
use App\AdditionalManipulation;

class EmployeeRepository
{
    //get employees
    public function getEmployees($search_string, $work_type)
    {
        try
        {
            $employees = Employee::with('workType', 'contractType', 'status')
                ->select('id', 'work_type_id', 'contract_type_id', 'name', 'oib', 'status_id')->where('id', '!=', 1);

            if ($search_string)
            {
                $employees->where(function($query) use ($search_string) {
                    $query->where('name', 'like', '%'.$search_string.'%')
                        ->orWhere('oib', 'like', '%'.$search_string.'%');
                });
            }

            if ($work_type)
            {
                $employees->where('work_type_id', '=', $work_type);
            }

            $employees = $employees->paginate(30);

            return ['status' => 1, 'data' => $employees];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //insert employee
    public function insertEmployee($code, $name, $work_type, $contract_type, $picture, $sex, $oib, $birth_date, $citizenship, $birth_city,
        $country, $city_id, $city, $address, $phone, $contract_start_date, $contract_expire_date, $medical_certificate_expire_date,
        $contract_end_date, $status, $create_account, $role, $email, $password)
    {
        try
        {
            //format birth date
            $birth_date = date('Y-m-d', strtotime($birth_date));

            //format contract start date
            $contract_start_date = date('Y-m-d', strtotime($contract_start_date));

            if ($contract_expire_date)
            {
                //format contract expire date
                $contract_expire_date = date('Y-m-d', strtotime($contract_expire_date));
            }

            if ($medical_certificate_expire_date)
            {
                //format medical certificate expire date
                $medical_certificate_expire_date = date('Y-m-d', strtotime($medical_certificate_expire_date));
            }

            if ($contract_end_date)
            {
                //format contract end date
                $contract_end_date = date('Y-m-d', strtotime($contract_end_date));
            }

            if ($country == 1)
            {
                //set city
                $city = City::find($city_id)->name;
            }

            //start transaction
            DB::beginTransaction();

            $employee = new Employee;
            $employee->code = $code;
            $employee->name = $name;
            $employee->work_type_id = $work_type;
            $employee->contract_type_id = $contract_type;
            $employee->sex = $sex;
            $employee->oib = $oib;
            $employee->birth_date = $birth_date;
            $employee->citizenship_id = $citizenship;
            $employee->birth_city = $birth_city;
            $employee->country_id = $country;
            $employee->city_id = $city_id;
            $employee->city = $city;
            $employee->address = $address;
            $employee->phone = $phone;
            $employee->contract_start_date = $contract_start_date;

            if ($contract_expire_date)
            {
                $employee->contract_expire_date = $contract_expire_date;
            }

            if ($medical_certificate_expire_date)
            {
                $employee->medical_certificate_expire_date = $medical_certificate_expire_date;
            }

            if ($contract_end_date)
            {
                $employee->contract_end_date = $contract_end_date;
            }

            $employee->status_id = $status;
            $employee->save();

            //call uploadPicture method from PictureRepository to upload picture
            $repo = new PictureRepository;
            $response = $repo->uploadPicture($picture, 'employees');

            //if response status = 0 return error message
            if ($response['status'] == 0)
            {
                return ['status' => 0];
            }

            //update picture
            $employee->picture = $response['data'];
            $employee->save();

            if ($create_account == 'T')
            {
                //call createAccount method from UserRepository to create user account
                $repo = new UserRepository;
                $response = $repo->createAccount($name, $role, $email, $password, $status);

                //if response status = 0 return error message
                if ($response['status'] == 0)
                {
                    return ['status' => 0];
                }

                //update user id
                $employee->user_id = $response['data'];
                $employee->save();
            }

            //commit transaction
            DB::commit();

            //set insert employee flash
            Session::flash('success_message', trans('main.employee_insert'));

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get employee details
    public function getEmployeeDetails($id)
    {
        try
        {
            $employee = Employee::find($id);

            //if employee doesn't exist return error message
            if (!$employee)
            {
                return ['status' => 0];
            }

            //format birth date
            $employee->birth_date = date('d.m.Y.', strtotime($employee->birth_date));

            //format contract start date
            $employee->contract_start_date = date('d.m.Y.', strtotime($employee->contract_start_date));

            if ($employee->contract_expire_date)
            {
                //format contract expire date
                $employee->contract_expire_date = date('d.m.Y.', strtotime($employee->contract_expire_date));
            }

            if ($employee->medical_certificate_expire_date)
            {
                //format medical certificate expire date
                $employee->medical_certificate_expire_date = date('d.m.Y.', strtotime($employee->medical_certificate_expire_date));
            }

            if ($employee->contract_end_date)
            {
                //format contract end date
                $employee->contract_end_date = date('d.m.Y.', strtotime($employee->contract_end_date));
            }

            //set picture path
            $employee->picture = URL::to('/').'/laravel/storage/app/public/employees/'.$employee->picture;

            //set default has account property
            $employee->has_account = 'F';

            if ($employee->user_id != null)
            {
                //update has account property
                $employee->has_account = 'T';

                //call getAccountData method to get account data
                $role = $this->getAccountData($id);

                //if response status = 0 return error message
                if ($role['status'] == 0)
                {
                    return ['status' => 0];
                }

                //set employee role property
                $employee->role_id = $role['role'];

                //set user email property
                $employee->email = $role['email'];
            }

            return ['status' => 1, 'data' => $employee];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //update employee
    public function updateEmployee($id, $code, $name, $work_type, $contract_type, $picture, $sex, $oib, $birth_date, $citizenship, $birth_city,
        $country, $city_id, $city, $address, $phone, $contract_start_date, $contract_expire_date, $medical_certificate_expire_date,
        $contract_end_date, $status, $create_account, $role, $email, $password, $new_picture)
    {
        try
        {
            //format birth date
            $birth_date = date('Y-m-d', strtotime($birth_date));

            //format contract start date
            $contract_start_date = date('Y-m-d', strtotime($contract_start_date));

            if ($contract_expire_date)
            {
                //format contract expire date
                $contract_expire_date = date('Y-m-d', strtotime($contract_expire_date));
            }

            if ($medical_certificate_expire_date)
            {
                //format medical certificate expire date
                $medical_certificate_expire_date = date('Y-m-d', strtotime($medical_certificate_expire_date));
            }

            if ($contract_end_date)
            {
                //format contract end date
                $contract_end_date = date('Y-m-d', strtotime($contract_end_date));
            }

            if ($country == 1)
            {
                //set city
                $city = City::find($city_id)->name;
            }

            //start transaction
            DB::beginTransaction();

            $employee = Employee::find($id);
            $employee->code = $code;
            $employee->name = $name;
            $employee->work_type_id = $work_type;
            $employee->contract_type_id = $contract_type;
            $employee->sex = $sex;
            $employee->oib = $oib;
            $employee->birth_date = $birth_date;
            $employee->citizenship_id = $citizenship;
            $employee->birth_city = $birth_city;
            $employee->country_id = $country;
            $employee->city_id = $city_id;
            $employee->city = $city;
            $employee->address = $address;
            $employee->phone = $phone;
            $employee->contract_start_date = $contract_start_date;

            if ($contract_expire_date)
            {
                $employee->contract_expire_date = $contract_expire_date;
            }
            else
            {
                $employee->contract_expire_date = NULL;
            }

            if ($medical_certificate_expire_date)
            {
                $employee->medical_certificate_expire_date = $medical_certificate_expire_date;
            }
            else
            {
                $employee->medical_certificate_expire_date = NULL;
            }

            if ($contract_end_date)
            {
                $employee->contract_end_date = $contract_end_date;
            }
            else
            {
                $employee->contract_end_date = NULL;
            }

            $employee->status_id = $status;
            $employee->save();

            if ($new_picture == 'T')
            {
                //call uploadPicture method from PictureRepository to upload picture
                $repo = new PictureRepository;
                $upload = $repo->uploadPicture($picture, 'employees');

                //if response status = 0 return error message
                if ($upload['status'] == 0)
                {
                    return ['status' => 0];
                }

                //call deletePicture method from PictureRepository to delete picture
                $repo = new PictureRepository;
                $delete = $repo->deletePicture($employee->picture, 'employees');

                //if response status = 0 return error message
                if ($delete['status'] == 0)
                {
                    return ['status' => 0];
                }

                //update picture
                $employee->picture = $upload['data'];
                $employee->save();
            }

            //check if employee has account
            $user_id = Employee::find($id)->user_id;

            if ($user_id)
            {
                //call updateAccount method from UserRepository to update user account
                $repo = new UserRepository;
                $response = $repo->updateAccount($user_id, $name, $role, $email, $password, $status);

                //if response status = 0 return error message
                if ($response['status'] == 0)
                {
                    return ['status' => 0];
                }
            }
            elseif ($create_account == 'T')
            {
                //call createAccount method from UserRepository to create user account
                $repo = new UserRepository;
                $response = $repo->createAccount($name, $role, $email, $password, $status);

                //if response status = 0 return error message
                if ($response['status'] == 0)
                {
                    return ['status' => 0];
                }

                //update user id
                $employee->user_id = $response['data'];
                $employee->save();
            }

            //commit transaction
            DB::commit();

            //set update employee flash
            Session::flash('success_message', trans('main.employee_update'));

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get account data
    public function getAccountData($employee)
    {
        try
        {
            //get user id
            $user_id = Employee::find($employee)->user_id;

            //get user role
            $role = RoleUser::select('role_id')->where('user_id', '=', $user_id)->first();

            //get user email
            $email = User::find($user_id)->email;

            return ['status' => 1, 'role' => $role->role_id, 'email' => $email];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get employees - select
    public function getEmployeesSelect($work_type)
    {
        try
        {
            //get employees
            $employees = Employee::select('id', 'name')->where('status_id', '=', 1)->where('work_type_id', '=', $work_type)
                ->where('id', '!=', 1)->get();

            //set employees array
            $employees_array = array();

            //loop through all employees
            foreach ($employees as $employee)
            {
                //add employee to employees array
                $employees_array[$employee->id] = $employee->name;
            }

            return ['status' => 1, 'data' => $employees_array];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get user employee id
    public function getUserEmployeeId()
    {
        if (UrlRequest::is('api/*'))
        {
            //get user from api token
            $user = Auth::guard('api')->user();
        }
        else
        {
            //get user
            $user = Auth::user();
        }

        $employee = Employee::select('id')->where('user_id', '=', $user->id)->first();

        return $employee->id;
    }

    //get user or employee work type
    public function getUserOrEmployeeWorkType($user_id = false, $employee_id = false)
    {
        $employee = Employee::select('work_type_id');

        if ($user_id)
        {
            $employee->where('user_id', '=', $user_id);
        }
        else
        {
            $employee->where('id', '=', $employee_id);
        }

        $employee = $employee->first();

        $work_type_id = $employee->work_type_id;

        //mehaničari
        if ($work_type_id >= 18 && $work_type_id <= 22)
        {
            $work_type = 2;
        }
        //strojari
        elseif ($work_type_id >= 29 && $work_type_id <= 36)
        {
            $work_type = 1;
        }
        //skladištari
        elseif ($work_type_id == 39)
        {
            $work_type = 3;
        }
        //poslovođe/voditelji gradilišta
        elseif (($work_type_id >= 27 && $work_type_id <= 28) || $work_type_id == 43)
        {
            $work_type = 4;
        }
        //ostali
        else
        {
            $work_type = 5;
        }

        return $work_type;
    }
}
