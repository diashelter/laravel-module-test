<?php

namespace Module\Order\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Module\Order\Controllers\OrderController;

class OrderRouteServiceProvider extends RouteServiceProvider
{
    public function map(): void
    {
        Route::prefix('/api')->group(function () {
            Route::apiResource('orders', OrderController::class);
        });
    }
}
