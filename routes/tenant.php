<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomainOrSubdomain;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::group([
    'prefix' => 'api',
    'middleware' => [
        InitializeTenancyByDomainOrSubdomain::class,
    ], // See the middleware group in Http Kernel
//    'as' => 'tenant.',
], function () {
    Route::group(
        [],
        function () {
            Route::get('users', [\App\Http\Controllers\TenantController::class, 'index']);
        }
    );
});

