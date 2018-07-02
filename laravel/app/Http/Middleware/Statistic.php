<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Statistic
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user->hasRole('HeadOfSite') || $user->hasRole('Manager') || $user->hasRole('Employee') || $user->hasRole('Mechanic'))
        {
            return redirect()->route('LoginPage');
        }

        return $next($request);
    }
}
