<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Dish;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure at least one user exists
        $user = User::first();

        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Default User',
                'email' => 'default@example.com',
                'password' => Hash::make('password'),
                'role' => 'user', // Adjust based on your User model
                'is_active' => true,
            ]);
        }

        // 2. Ensure there are dishes in the database
        if (!Dish::exists()) {
            $this->command->warn("⚠️ No dishes found. Please seed dishes first.");
            return;
        }

        // 3. Generate 10 orders
        for ($i = 0; $i < 10; $i++) {
            // Random dish
            $dish = Dish::inRandomOrder()->first();

            // Calculate total price based on quantity and dish price
            $quantity = rand(1, 5);
            $totalPrice = $dish->price * $quantity;

            // Create the order
            Order::create([
                'user_id' => $user->id,
                'dish_id' => $dish->id,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'customer_name' => fake()->name,
                'phone' => fake()->phoneNumber,
                'address' => fake()->address,
                'special_instructions' => fake()->sentence(6),
                'status' => ['pending', 'confirmed', 'processing', 'completed', 'cancelled'][rand(0, 4)],
                'preferred_delivery_time' => now()->addHours(rand(1, 24))->toDateTimeString(),
            ]);
        }

        $this->command->info("✅ 10 orders seeded successfully.");
    }
}
