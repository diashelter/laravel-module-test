<?php

namespace Module\Product\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Module\Product\Controllers\ProductController;

class ProductRouteServiceProvider extends RouteServiceProvider
{
    public function map(): void
    {
        Route::prefix('api')->group(function () {
            Route::apiResource('products', ProductController::class);
        });
    }
}
