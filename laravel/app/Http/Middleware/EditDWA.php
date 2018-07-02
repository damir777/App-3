<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EditDWA
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user->hasRole('Admin') || $user->hasRole('HeadOfSite') || $user->hasRole('Manager'))
        {
            return $next($request);
        }

        if ($request->ajax() || $request->wantsJson())
        {
            return response()->json(['status' => 0, 'error' => trans('errors.permission_denied')]);
        }
        else
        {
            return redirect()->route('LoginPage');
        }
    }
}
