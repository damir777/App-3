<?php

namespace App\CustomValidator;

use Illuminate\Support\Facades\Validator as DefaultValidator;
use Illuminate\Support\Facades\Request as Request;
use Illuminate\Validation\Rule;
use App\Machine;
use App\Tool;
use App\Equipment;
use App\Vehicle;
use App\Employee;
use App\Site;
use App\Parking;
use App\AdditionalManipulation;
use App\Repositories\ManipulationRepository;
use App\Repositories\EmployeeRepository;

/*
|--------------------------------------------------------------------------
| Custom validation class for code, oib, email and password validation
|--------------------------------------------------------------------------
*/

class Validator
{
    //validate form
    public static function validate($type, $fields, $id = false)
    {
        //set tables array
        $tables = array('machine' => 'machines', 'tool' => 'tools', 'equipment' => 'equipment', 'vehicle' => 'vehicles',
            'employee' => 'employees', 'site' => 'sites');

        //get form input
        $input = Request::all();

        //set default update id
        $update_id = false;

        if (isset($input['id']))
        {
            //set update id
            $update_id = $id;
        }

        foreach ($fields as $field)
        {
            switch ($field)
            {
                case 'code':
                    $response = self::codeValidation($type, $tables[$type], $input, $update_id);
                    break;
                case 'oib':
                    $response = self::OIBValidation($type, $tables[$type], $input, $update_id);
                    break;
                case 'email':
                    $response = self::emailValidation($input, $update_id);
                    break;
                case 'password':
                    $response = self::passwordValidation($input, $update_id);
                    break;
                default:
                    $response = true;
            }

            if (!$response['status'])
            {
                return ['status' => $response['status'], 'error' => $response['error']];
            }
        }

        return ['status' => true];
    }

    private static function codeValidation($type, $table, $input, $id)
    {
        //set error message
        $error_message = trans('errors.general_code_uniqueness');

        if ($type == 'employee')
        {
            //set employee error message
            $error_message = trans('errors.general_employee_code_uniqueness');
        }

        //get code
        $code = $input['code'];

        //set default return array
        $return_array = ['status' => true];

        //set default rules array
        $rules = array();

        if ($id)
        {
            /*
            |--------------------------------------------------------------------------
            | Update code validation rules
            |--------------------------------------------------------------------------
            */

            $rules['code'] = Rule::unique($table)->ignore($id);
        }
        else
        {
            /*
            |--------------------------------------------------------------------------
            | Insert code validation rules
            |--------------------------------------------------------------------------
            */

            $rules['code'] = 'unique:'.$table;
        }

        $validation = DefaultValidator::make(['code' => $code], $rules);

        //if code is not unique return error message
        if (!$validation->passes())
        {
            $return_array = ['status' => false, 'error' => trans('main.'.$type).$error_message];
        }

        return $return_array;
    }

    private static function OIBValidation($type, $table, $input, $id)
    {
        //set error message
        $error_message = trans('errors.general_oib_uniqueness');

        //get oib
        $oib = $input['oib'];

        //set default return array
        $return_array = ['status' => true];

        //set default rules array
        $rules = array();

        if ($id)
        {
            /*
            |--------------------------------------------------------------------------
            | Update oib validation rules
            |--------------------------------------------------------------------------
            */

            $rules['oib'] = Rule::unique($table)->ignore($id);
        }
        else
        {
            /*
            |--------------------------------------------------------------------------
            | Insert oib validation rules
            |--------------------------------------------------------------------------
            */

            $rules['oib'] = 'unique:'.$table;
        }

        $validation = DefaultValidator::make(['oib' => $oib], $rules);

        //if oib is not unique return error message
        if (!$validation->passes())
        {
            $return_array = ['status' => false, 'error' => trans('main.'.$type).$error_message];
        }

        return $return_array;
    }

    private static function emailValidation($input, $id)
    {
        //get create account and email
        $create_account = $input['create_account'];
        $email = $input['email'];

        //set default return array
        $return_array = ['status' => true];

        if ($id)
        {
            /*
            |--------------------------------------------------------------------------
            | Update email validation
            |--------------------------------------------------------------------------
            */

            $employee_id = $input['id'];

            $user_id = Employee::find($employee_id)->user_id;

            if ($user_id)
            {
                $return_array = self::validateEmail($email, $user_id);
            }
            elseif ($create_account == 'T')
            {
                $return_array = self::validateEmail($email);
            }
        }
        else
        {
            /*
            |--------------------------------------------------------------------------
            | Insert email validation
            |--------------------------------------------------------------------------
            */

            if ($create_account == 'T')
            {
                $return_array = self::validateEmail($email);
            }
        }

        return $return_array;
    }

    private static function passwordValidation($input, $id)
    {
        //get create account, password and password confirmation
        $create_account = $input['create_account'];
        $password = $input['password'];
        $password_confirmation = $input['password_confirmation'];

        //set default return array
        $return_array = ['status' => true];

        if ($id)
        {
            /*
            |--------------------------------------------------------------------------
            | Update password validation
            |--------------------------------------------------------------------------
            */

            $employee_id = $input['id'];

            $user_id = Employee::find($employee_id)->user_id;

            if ($user_id)
            {
                if ($password != null)
                {
                    $return_array = self::validatePassword($password, $password_confirmation);
                }
            }
            elseif ($create_account == 'T')
            {
                $return_array = self::validatePassword($password, $password_confirmation);
            }
        }
        else
        {
            /*
            |--------------------------------------------------------------------------
            | Insert password validation
            |--------------------------------------------------------------------------
            */

            if ($create_account == 'T')
            {
                $return_array = self::validatePassword($password, $password_confirmation);
            }
        }

        return $return_array;
    }

    private static function validateEmail($email, $id = false)
    {
        //set required error message
        $required_error_message = trans('errors.general_required');

        //set format error message
        $format_error_message = trans('errors.email_format');

        //set uniqueness error message
        $uniqueness_error_message = trans('errors.general_email_uniqueness');

        //if email doesn't exist return error message
        if ($email == null)
        {
            return ['status' => false, 'error' => trans('main.email').$required_error_message];
        }

        $validation = DefaultValidator::make(['email' => $email], ['email' => 'email']);

        //if email is not valid email address return error message
        if (!$validation->passes())
        {
            return ['status' => false, 'error' => $format_error_message];
        }

        if ($id)
        {
            $rules['email'] = Rule::unique('users')->ignore($id);
        }
        else
        {
            $rules['email'] = 'unique:users';
        }

        $validation = DefaultValidator::make(['email' => $email], $rules);

        //if email is not unique return error message
        if (!$validation->passes())
        {
            return ['status' => false, 'error' => trans('main.user').$uniqueness_error_message];
        }

        return ['status' => true];
    }

    private static function validatePassword($password, $password_confirmation)
    {
        //set required error message
        $required_error_message = trans('errors.general_required');

        //set password confirmation error message
        $password_confirmation_error_message = trans('errors.password_confirmation_');

        //if password doesn't exist return error message
        if ($password == null)
        {
            return ['status' => false, 'error' => trans('main.password').$required_error_message];
        }

        $validation = DefaultValidator::make(['password' => $password, 'password_confirmation' => $password_confirmation],
            ['password' => 'confirmed']);

        //if password is not confirmed return error message
        if (!$validation->passes())
        {
            return ['status' => false, 'error' => $password_confirmation_error_message];
        }

        return ['status' => true];
    }

    public static function validateManipulation($manipulation_type, $location_id, $resource_type, $resource_id)
    {
        //set manipulation types array
        $manipulation_types_array = ['status', 'site', 'parking', 'additional', 'remove_additional'];

        if (!in_array($manipulation_type, $manipulation_types_array))
        {
            return ['status' => false, 'error' => trans('errors.error')];
        }

        //set resources array
        $resources_array = [
            1 => ['model' => Machine::where('status_id', '=', 1), 'name' => trans('main.machine')],
            2 => ['model' => Tool::where('status_id', '=', 1), 'name' => trans('main.tool')],
            3 => ['model' => Equipment::where('status_id', '=', 1), 'name' => trans('main.equipment')],
            4 => ['model' => Vehicle::where('status_id', '=', 1), 'name' => trans('main.vehicle')],
            5 => ['model' => Employee::where('status_id', '=', 1)->where('id', '!=', 1), 'name' => trans('main.employee')]];

        if (!in_array($resource_type, [1, 2, 3, 4, 5]))
        {
            return ['status' => false, 'error' => trans('errors.invalid_resource_type')];
        }

        $check_resource = $resources_array[$resource_type]['model']->where('id', '=', $resource_id)->first();

        if (!$check_resource)
        {
            //set error string
            $error_string = trans_choice('errors.invalid_resource', $resource_type,
                ['resource' => strtolower($resources_array[$resource_type]['name'])]);

            return ['status' => false, 'error' => $error_string];
        }

        if ($manipulation_type == 'status')
        {
            if ($resource_type != 5)
            {
                return ['status' => false, 'error' => trans('errors.invalid_resource_type')];
            }

            $response = self::changeStatusValidation($location_id, $resource_type, $resource_id);
        }
        elseif ($manipulation_type == 'site' || $manipulation_type == 'additional')
        {
            $response = self::siteManipulationValidation($manipulation_type, $location_id, $resource_type, $resource_id, $resources_array);
        }
        elseif ($manipulation_type == 'parking')
        {
            if (!in_array($resource_type, [1, 2, 3, 4]))
            {
                return ['status' => false, 'error' => trans('errors.invalid_resource_type')];
            }

            $response = self::parkingManipulationValidation($location_id, $resource_type, $resource_id, $resources_array);
        }
        else
        {
            $response = self::removeAdditionalValidation($location_id, $resource_id);
        }

        if (!$response['status'])
        {
            return ['status' => $response['status'], 'error' => $response['error']];
        }

        return ['status' => true];
    }

    public static function changeStatusValidation($status_id, $resource_type, $resource_id)
    {
        //set status array
        $status_array = [1, 2, 3];

        //set status names error array
        $status_names_error_array = ['godišnjem odmoru', 'bolovanju', 'slobodnim danima'];

        if (!in_array($status_id, $status_array))
        {
            return ['status' => false, 'error' => trans('errors.invalid_status')];
        }

        //call getResourceLocation method from ManipulationRepository to get resource current status
        $repo = new ManipulationRepository;
        $resource_status = $repo->getResourceLocation('status', $resource_type, $resource_id);

        if ($status_id == $resource_status)
        {
            //set error string
            $error_string = trans('errors.status_resource_already_exists', ['status' => $status_names_error_array[$status_id - 1]]);

            return ['status' => false, 'error' => $error_string];
        }

        return ['status' => true];
    }

    public static function siteManipulationValidation($manipulation_type, $site_id, $resource_type, $resource_id, $resources_array)
    {
        $check_site = Site::where('status_id', '=', 1)->where('id', '=', $site_id)->first();

        if (!$check_site)
        {
            return ['status' => false, 'error' => trans('errors.invalid_site')];
        }

        //call getResourceLocation method from ManipulationRepository to get resource current site
        $repo = new ManipulationRepository;
        $resource_site = $repo->getResourceLocation($manipulation_type, $resource_type, $resource_id, $site_id);

        if ($site_id == $resource_site)
        {
            //set error string
            $error_string = trans('errors.site_resource_already_exists', ['resource' => $resources_array[$resource_type]['name']]);

            return ['status' => false, 'error' => $error_string];
        }

        return ['status' => true];
    }

    public static function parkingManipulationValidation($parking_id, $resource_type, $resource_id, $resources_array)
    {
        $check_parking = Parking::where('status_id', '=', 1)->where('id', '=', $parking_id)->first();

        if (!$check_parking)
        {
            return ['status' => false, 'error' => trans('errors.invalid_parking')];
        }

        //call getResourceLocation method from ManipulationRepository to get resource current parking
        $repo = new ManipulationRepository;
        $resource_parking = $repo->getResourceLocation('parking', $resource_type, $resource_id);

        if ($parking_id == $resource_parking)
        {
            //set error string
            $error_string = trans('errors.parking_resource_already_exists', ['resource' => $resources_array[$resource_type]['name']]);

            return ['status' => false, 'error' => $error_string];
        }

        return ['status' => true];
    }

    public static function removeAdditionalValidation($site_id, $resource_id)
    {
        $check_site = Site::where('status_id', '=', 1)->where('id', '=', $site_id)->first();

        if (!$check_site)
        {
            return ['status' => false, 'error' => trans('errors.invalid_site')];
        }

        //get employees additional site
        $site = AdditionalManipulation::where('site_id', '=', $site_id)->where('resource_type', '=', 5)->where('resource_id', '=', $resource_id)
            ->whereNull('end_time')->first();

        if (!$site)
        {
            //set error string
            $error_string = trans('errors.employee_not_on_site');

            return ['status' => false, 'error' => $error_string];
        }

        return ['status' => true];
    }

    public static function currentSiteResourcesValidation($is_edit, $site_id, $resource_array, $dwa_id)
    {
        //set resources array
        $resource_names_array = [
            1 => trans('main.machine'), 2 => trans('main.tool'), 3 => trans('main.equipment'), 4 => trans('main.vehicle'),
            5 => trans('main.employee')];

        foreach ($resource_array as $resource)
        {
            //call getUserOrEmployeeWorkType method from EmployeeRepository to get employee work type
            $repo = new EmployeeRepository;
            $work_type = $repo->getUserOrEmployeeWorkType(null, $resource[1]);

            //if resource type = '5' and employee work type != '1' and '5' skip resource site validation
            if ($resource[0] == 5 && $work_type != 1 && $work_type != 5)
            {
                continue;
            }

            $repo = new ManipulationRepository;

            if ($is_edit == 'F')
            {
                //call checkCurrentSiteResource method from ManipulationRepository to check current site resource
                $resource_check = $repo->checkCurrentSiteResource($site_id, $resource[0], $resource[1]);
            }
            else
            {
                //call checkHistorySiteResource method from ManipulationRepository to check history site resource
                $resource_check = $repo->checkHistorySiteResource($site_id, $resource[0], $resource[1], $dwa_id);
            }

            if (!$resource_check)
            {
                //set warning string
                $warning_string = trans('errors.dwa_resource_is_not_on_employee_site', ['resource' => $resource_names_array[$resource[0]]]);

                return ['status' => false, 'warning' => $warning_string];
            }
        }

        return ['status' => true];
    }
}
