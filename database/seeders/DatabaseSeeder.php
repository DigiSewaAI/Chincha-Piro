<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. First: Seed Users (Orders require user_id)
        $this->call(UserSeeder::class);

        // 2. Second: Seed Categories (Dishes require category_id)
        $this->call(CategorySeeder::class);

        // 3. Third: Seed Dishes (Use DishSeeder instead of MenusTableSeeder)
        $this->call(DishSeeder::class);

        // 4. Fourth: Seed Orders (requires valid user_id and dish_id)
        $this->call(OrderSeeder::class);

        // Optional Seeders (uncomment as needed)
        // $this->call(ReservationSeeder::class);
        // $this->call(TableSeeder::class);
    }
}
