<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Repositories\GeneralRepository;

class AuthController extends Controller
{
    //get login page
    public function getLoginPage()
    {
        return view('auth.login');
    }

    //login user
    public function loginUser(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        //if login fails redirect to login page
        if (!Auth::attempt(['email' => $email, 'password' => $password, 'active' => 'T']))
        {
            return redirect()->route('LoginPage')->with('error_message', trans('errors.login_error'));
        }

        //call getUserHomePageRoute method from GeneralRepository to get user home page route
        $repo = new GeneralRepository;
        $route = $repo->getUserHomePageRoute();

        return redirect()->route($route);
    }

    //logout user
    public function logoutUser()
    {
        //logout user
        Auth::logout();

        //clear all session variables
        Session::flush();

        return redirect()->route('LoginPage');
    }
}
