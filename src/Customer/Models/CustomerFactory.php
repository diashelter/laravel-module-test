<?php

namespace Module\Customer\Models;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'date_of_birth' => $this->faker->date(),
            'address' => $this->faker->address(),
            'complement' => $this->faker->text(),
            'neighborhood' => $this->faker->country(),
            'zipcode' => $this->faker->postcode(),
        ];
    }
}
