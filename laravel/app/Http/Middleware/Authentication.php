<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authentication
{
    public function handle($request, Closure $next)
    {
        if (!Auth::user())
        {
            if ($request->ajax() || $request->wantsJson())
            {
                return response()->json(['status' => 401]);
            }
            else
            {
                return redirect()->route('LoginPage');
            }
        }

        return $next($request);
    }
}
