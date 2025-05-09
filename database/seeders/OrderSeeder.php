<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Dish;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure at least one user exists
        $user = User::first();

        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Default User',
                'email' => 'default@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Generate 10 Orders with user_id
        for ($i = 0; $i < 10; $i++) {
            Order::create([
                'user_id' => $user->id,
                'dish_id' => Dish::inRandomOrder()->first()->id,
                'quantity' => rand(1, 5),
                'total_price' => rand(100, 500),
                'customer_name' => fake()->name,
                'phone' => fake()->phoneNumber,
                'address' => fake()->address,
                'status' => ['pending', 'processing', 'completed'][rand(0, 2)],
            ]);
        }
    }
}
