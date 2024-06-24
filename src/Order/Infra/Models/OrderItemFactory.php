<?php

namespace Module\Order\Infra\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Module\Product\Model\Product;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'amount_in_cents' => $this->faker->numberBetween(1000, 9999999),
        ];
    }
}
