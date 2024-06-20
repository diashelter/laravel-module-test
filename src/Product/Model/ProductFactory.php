<?php

namespace Module\Product\Model;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'price_in_cents' => $this->faker->numberBetween(1000, 10000),
            'photo' => $this->faker->imageUrl(),
        ];
    }
}
