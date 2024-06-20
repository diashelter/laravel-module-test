<?php

return [
    App\Providers\AppServiceProvider::class,
    \Module\Customer\Providers\CustomersRouteServiceProvider::class,
    \Module\Product\Providers\ProductRouteServiceProvider::class,
    \Module\Order\Providers\OrderRouteServiceProvider::class,
];
