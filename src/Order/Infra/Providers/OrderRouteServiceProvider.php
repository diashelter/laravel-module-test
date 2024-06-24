<?php

namespace Module\Order\Infra\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Module\Order\Infra\Controllers\OrderController;

class OrderRouteServiceProvider extends RouteServiceProvider
{
    public function map(): void
    {
        Route::prefix('/api')->group(function () {
            Route::apiResource('orders', OrderController::class);
        });
    }
}
