<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Request as Request;

class AppServiceProvider extends ServiceProvider
{
    //set username variable
    private $username;

    //set user role variable
    private $user_role;

    //set roles functionality
    private $show_dashboard = 'T';
    private $show_overview = 'T';
    private $show_sites_and_parking = 'T';
    private $show_entry_dwa = 'T';
    private $can_edit_dwa = 'F';
    private $show_report_seen = 'T';
    private $show_resources = 'T';
    private $show_statistic = 'T';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
        |--------------------------------------------------------------------------
        | Share menu view with all views
        |--------------------------------------------------------------------------
        */

        $this->app['events']->listen(Authenticated::class, function ($e) {

            if ($e->user->hasRole('Admin'))
            {
                $this->can_edit_dwa = 'T';
            }
            elseif ($e->user->hasRole('Management'))
            {
                $this->show_entry_dwa = 'F';
                $this->show_report_seen = 'F';
            }
            elseif ($e->user->hasRole('HeadOfSite'))
            {
                $this->show_report_seen = 'F';
                $this->can_edit_dwa = 'T';
                $this->show_statistic = 'F';
            }
            elseif ($e->user->hasRole('Manager'))
            {
                $this->show_sites_and_parking = 'F';
                $this->show_report_seen = 'F';
                $this->can_edit_dwa = 'T';
                $this->show_statistic = 'F';
            }
            elseif ($e->user->hasRole('Employee'))
            {
                $this->show_dashboard = 'F';
                $this->show_overview = 'F';
                $this->show_sites_and_parking = 'F';
                $this->show_report_seen = 'F';
                $this->show_resources = 'F';
                $this->show_statistic = 'F';
            }
            elseif ($e->user->hasRole('Mechanic'))
            {
                $this->show_dashboard = 'F';
                $this->show_overview = 'F';
                $this->show_sites_and_parking = 'F';
                $this->show_resources = 'F';
                $this->show_statistic = 'F';
            }

            //set username and user role
            $this->username = $e->user->name;
            $this->user_role = $e->user->roles;

            view()->share('username', $this->username);
            view()->share('user_role', $this->user_role[0]->display_name);

            view()->share('show_dashboard', $this->show_dashboard);
            view()->share('show_overview', $this->show_overview);
            view()->share('show_sites_and_parking', $this->show_sites_and_parking);
            view()->share('show_entry_dwa', $this->show_entry_dwa);
            view()->share('can_edit_dwa', $this->can_edit_dwa);
            view()->share('show_report_seen', $this->show_report_seen);
            view()->share('show_resources', $this->show_resources);
            view()->share('show_statistic', $this->show_statistic);
        });

        /*
        |--------------------------------------------------------------------------
        | Additional validation rules
        |--------------------------------------------------------------------------
        */

        Validator::extend('custom_date', function($attribute, $value, $parameters, $validator)
        {
            return $value = preg_match('/^[0-9]{2}\.[0-9]{2}\.[0-9]{4}\.$/', $value);
        });

        Validator::extend('year', function($attribute, $value, $parameters, $validator)
        {
            return $value = preg_match('/^[0-9]{4}$/', $value);
        });

        Validator::extend('oib', function($attribute, $value, $parameters, $validator)
        {
            return $value = preg_match('/^[0-9]{11}$/', $value);
        });

        Validator::extend('time', function($attribute, $value, $parameters, $validator)
        {
            return $value = preg_match('/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/', $value);
        });

        Validator::extend('end_time', function($attribute, $value, $parameters, $validator)
        {
            //set default validation variable
            $validation = true;

            //get form input
            $input = Request::all();
            $start_time = $input['start_time'];

            //format start and end time
            $start_time = date('H:i:s', strtotime($start_time.':00'));
            $end_time = date('H:i:s', strtotime($value.':00'));

            if ($end_time == $start_time || $end_time < $start_time)
            {
                $validation = false;
            }

            return $validation;
        });

        Validator::extend('end_working_hours', function($attribute, $value, $parameters, $validator)
        {
            //set default validation variable
            $validation = true;

            //get form input
            $input = Request::all();
            $activity = $input['activity'];
            $start_working_hours = $input['start_working_hours'];

            if ($activity != 66)
            {
                if ($value == $start_working_hours || $value < $start_working_hours)
                {
                    $validation = false;
                }
            }
            else
            {
                if ($start_working_hours != $value)
                {
                    if ($value == $start_working_hours || $value < $start_working_hours)
                    {
                        $validation = false;
                    }
                }
            }

            return $validation;
        });

        /*
        |--------------------------------------------------------------------------
        | Set default string length for SQL indexes
        |--------------------------------------------------------------------------
        */

        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
