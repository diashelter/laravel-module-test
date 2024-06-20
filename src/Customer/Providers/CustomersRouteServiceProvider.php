<?php

namespace Module\Customer\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Module\Customer\Controllers\CustomerController;

class CustomersRouteServiceProvider extends RouteServiceProvider
{
    public function map(): void
    {
        Route::prefix('api')->group(function () {
            Route::apiResource('/customers', CustomerController::class);
        });
    }
}
