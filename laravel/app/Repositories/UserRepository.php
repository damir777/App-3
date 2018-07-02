<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as UrlRequest;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Role;
use App\RoleUser;

class UserRepository
{
    //create user account
    public function createAccount($name, $role, $email, $password, $status)
    {
        try
        {
            $user = new User;
            $user->name = $name;
            $user->email = $email;
            $user->password = Hash::make($password);

            $active = 'T';

            if ($status == 2)
            {
                $active = 'F';
            }

            $user->active = $active;
            $user->save();

            //get role model
            $role_model = Role::find($role);

            //attach user role
            $user->attachRole($role_model);

            return ['status' => 1, 'data' => $user->id];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //update user account
    public function updateAccount($user_id, $name, $role, $email, $password, $status)
    {
        try
        {
            $user = User::find($user_id);
            $user->name = $name;
            $user->email = $email;

            if ($password != null)
            {
                $user->password = Hash::make($password);
            }

            $active = 'T';

            if ($status == 2)
            {
                $active = 'F';
            }

            $user->active = $active;
            $user->save();

            //check user role
            $check_role = RoleUser::where('user_id', '=', $user_id)->where('role_id', '=', $role)->first();

            //if user role has been changed, delete old role and attach new role
            if (!$check_role)
            {
                //get old user role
                $old_role = RoleUser::select('role_id')->where('user_id', '=', $user_id)->first();

                //delete old user role
                RoleUser::where('user_id', '=', $user_id)->where('role_id', '=', $old_role->role_id)->delete();

                //get role model
                $role_model = Role::find($role);

                //attach new user role
                $user->attachRole($role_model);
            }

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //get authenticated user
    public function getAuthenticatedUser()
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

        return $user;
    }
}
