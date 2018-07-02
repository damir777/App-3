<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Repositories\GeneralRepository;

class APIAuthorization
{
    public function handle($request, Closure $next)
    {
        $key = $request->headers->get('App-Key');
        $secret = $request->headers->get('App-Secret');

        //if key and secret doesn't match app values return status 403
        if ($key != 'xx' || $secret != 'NyGapyXjM0Mhlci0yez3DgybVxd9TZ656mb0gdwt')
        {
            return response()->json(['status' => 403]);
        }

        return $next($request);
    }
}
