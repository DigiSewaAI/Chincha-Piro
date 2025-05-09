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
        // ✅ सीडरहरूको क्रम समायोजन गरिएको (अस्तित्वमा रहेको फाइलहरूको लागि)
        $this->call([
            UserSeeder::class,           // 1. पहिले UserSeeder (OrderSeeder ले user_id मान्छ)
            MenusTableSeeder::class,     // 2. नाम परिवर्तन गरिएको (MenuSeeder सट्टा MenusTableSeeder)
            OrderSeeder::class,          // 3. OrderSeeder (user_id र dish_id आवश्यक)
            // ReservationSeeder::class,  // 4. आवश्यकता भएमा
            // CategorySeeder::class,     // 5. आवश्यकता भएमा
        ]);
    }
}
