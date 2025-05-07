<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\DishSeeder;   // DishSeeder आयात गरियो
use Database\Seeders\OrderSeeder; // OrderSeeder आयात गरियो

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // पहिले DishSeeder चलाउनुहोस् (Orderहरूले dish_id मान्छन्)
        $this->call([
            DishSeeder::class,
        ]);

        // त्यसपछि OrderSeeder चलाउनुहोस्
        $this->call([
            OrderSeeder::class,
        ]);
    }
}
