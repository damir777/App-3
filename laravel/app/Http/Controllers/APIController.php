<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\EmployeeRepository;

class APIController extends Controller
{
    //set repo variable
    protected $repo;

    //login user
    public function loginUser(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        //if login fails return error message
        if (!Auth::attempt(['email' => $username, 'password' => $password, 'active' => 'T']))
        {
            return response()->json(['status' => 2]);
        }

        $user = Auth::user();

        //if user doesn't have 'User' role return error message
        if ($user->hasRole('Admin') || $user->hasRole('Management') || $user->hasRole('HeadOfDepartment'))
        {
            //logout user
            Auth::logout();

            return response()->json(['status' => 0]);
        }

        //generate api token
        $token = substr(md5(rand()), 0, 50);

        //update user api token
        $user->api_token = $token;
        $user->save();

        //call getUserOrEmployeeWorkType method from EmployeeRepository to get user work type
        $repo = new EmployeeRepository;
        $work_type = $repo->getUserOrEmployeeWorkType($user->id, null);

        return response()->json(['status' => 1, 'api_token' => $token, 'work_type' => $work_type]);
    }

    //logout user
    public function logoutUser()
    {
        $user = Auth::guard('api')->user();

        //delete user api token
        $user->api_token = '';
        $user->save();

        return response()->json(['status' => 1]);
    }
}
