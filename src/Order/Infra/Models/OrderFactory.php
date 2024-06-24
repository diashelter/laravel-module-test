<?php

namespace Module\Order\Infra\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Module\Customer\Models\Customer;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'amount_in_cents' => $this->faker->numberBetween(1000, 999999),
        ];
    }
}
