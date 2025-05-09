<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dish; // Dish मोडेल आयात गरिएको

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // १० वटा डिशहरू सिर्जना गर्नुहोस्
        Dish::factory(10)->create();
    }
}
