<?php

namespace Module\Order\Infra\Providers;

use App\Providers\AppServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Module\Order\Domain\OrderRepository;
use Module\Order\Infra\Repository\OrderRepositoryEloquent;

class OrderServiceProvider extends AppServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OrderRepository::class, function (Application $app) {
            return $app->make(OrderRepositoryEloquent::class);
        });
    }
}
