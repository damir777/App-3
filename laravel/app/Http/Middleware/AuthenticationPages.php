<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Repositories\GeneralRepository;

class AuthenticationPages
{
    public function handle($request, Closure $next)
    {
        //get user
        $user = Auth::user();

        if ($user)
        {
            //call getUserHomePageRoute method from GeneralRepository to get user home page route
            $repo = new GeneralRepository;
            $route = $repo->getUserHomePageRoute();

            return redirect()->route($route);
        }

        return $next($request);
    }
}
