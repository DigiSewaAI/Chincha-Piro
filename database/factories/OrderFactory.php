<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            // User relationship
            'user_id' => User::factory(), // User मोडलबाट स्वत: नयाँ प्रयोगकर्ता सिर्जना गर्नुहोस्

            // Customer details
            'customer_name' => $this->faker->name(),
            'phone' => '98' . $this->faker->numerify('########'), // नेपाली फोन नम्बर (उदाहरण: 9841234567)
            'address' => $this->faker->address(),

            // Order details
            'special_instructions' => $this->faker->sentence(), // छोटो विशेष निर्देशन
            'preferred_delivery_time' => $this->faker->dateTimeBetween('now', '+1 week'), // १ हप्ताभित्रको डिलिभरी समय
            'payment_method' => $this->faker->randomElement(['cash', 'khalti']), // केवल 'cash' वा 'khalti'
            'status' => $this->faker->randomElement(['pending', 'confirmed']), // केवल 'pending' वा 'confirmed'
            'total_price' => $this->faker->randomFloat(2, 500, 5000), // सही column: total_price

            // Boolean flags
            'is_public' => $this->faker->boolean(70), // ७०% सार्वजनिक अर्डर
            'is_paid' => $this->faker->boolean(80),   // ८०% भुक्तान भएको अर्डर
        ];
    }
}
