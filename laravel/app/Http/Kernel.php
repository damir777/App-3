<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
            \Barryvdh\Cors\HandleCors::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,

        //custom authentication middleware
        'authentication' => \App\Http\Middleware\Authentication::class,

        //custom authentication pages middleware
        'login' => \App\Http\Middleware\AuthenticationPages::class,

        //custom API authorization middleware
        'APIAuth' => \App\Http\Middleware\APIAuthorization::class,

        //custom dashboard middleware
        'dashboard' => \App\Http\Middleware\Dashboard::class,

        //custom overview middleware
        'overview' => \App\Http\Middleware\Overview::class,

        //custom sites and parking middleware
        'sitesAndParking' => \App\Http\Middleware\SitesAndParking::class,

        //custom entry dwa middleware
        'entryDWA' => \App\Http\Middleware\EntryDWA::class,

        //custom edit dwa middleware
        'editDWA' => \App\Http\Middleware\EditDWA::class,

        //custom edit dwa middleware
        'confirmDWA' => \App\Http\Middleware\ConfirmDWA::class,

        //custom report seen middleware
        'reportSeen' => \App\Http\Middleware\ReportSeen::class,

        //custom resources middleware
        'resources' => \App\Http\Middleware\Resources::class,

        //custom statistic middleware
        'statistic' => \App\Http\Middleware\Statistic::class
    ];
}
