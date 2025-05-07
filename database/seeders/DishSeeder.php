<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dish; // सही मोडेल आयात गरियो

class DishSeeder extends Seeder
{
    public function run()
    {
        // 10 वटा डिशहरू सिर्जना गर्नुहोस्
        \App\Models\Dish::factory(10)->create();
    }
}
