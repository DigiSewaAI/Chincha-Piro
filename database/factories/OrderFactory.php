<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            // User सम्बन्ध
            'user_id' => User::factory(),

            // ग्राहक विवरण
            'customer_name' => $this->faker->name(),
            'customer_phone' => '98' . $this->faker->numerify('########'), // नेपाली शैलीमा फोन
            'delivery_address' => $this->faker->address(),

            // अर्डर विवरण
            'special_instructions' => $this->faker->sentence(),
            'preferred_delivery_time' => $this->faker->dateTimeBetween('now', '+1 week'),
            'payment_method' => $this->faker->randomElement(['cash', 'khalti']),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'delivered', 'cancelled']),
            'total_price' => $this->faker->randomFloat(2, 500, 5000),

            // Boolean flags
            'is_paid' => $this->faker->boolean(80),
            'is_public' => $this->faker->boolean(70),
        ];
    }
}
