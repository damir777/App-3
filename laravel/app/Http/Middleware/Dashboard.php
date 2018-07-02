<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Dashboard
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user->hasRole('Employee') || $user->hasRole('Mechanic'))
        {
            if ($request->ajax() || $request->wantsJson())
            {
                return response()->json(['status' => 0, 'error' => trans('errors.permission_denied')]);
            }
            else
            {
                return redirect()->route('LoginPage');
            }
        }

        return $next($request);
    }
}
