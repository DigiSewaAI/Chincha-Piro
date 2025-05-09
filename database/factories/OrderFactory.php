<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'dish_id' => rand(1, 10), // 1–10 बीचको र्यान्डम dish_id
            'quantity' => rand(1, 5),
            'total_price' => rand(100, 500),
            'customer_name' => $this->faker->name(),
            'phone' => '98' . rand(10000000, 99999999),
            'address' => $this->faker->address(),
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed']),
        ];
    }
}
