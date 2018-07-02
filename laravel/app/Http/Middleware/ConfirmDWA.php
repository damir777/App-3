<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ConfirmDWA
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user->hasRole('HeadOfSite') && !$user->hasRole('Manager'))
        {
            return redirect()->route('LoginPage');
        }

        return $next($request);
    }
}
