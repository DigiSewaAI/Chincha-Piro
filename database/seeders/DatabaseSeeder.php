<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * डेटाबेसको लागि डमी डाटा सिड गर्नुहोस्
     */
    public function run(): void
    {
        // १. पहिलो चरण: प्रयोगकर्ता सिड गर्ने (अर्डरहरूले user_id आवश्यक पर्छ)
        $this->call(UserSeeder::class);

        // २. दोस्रो चरण: श्रेणीहरू सिड गर्ने (खानाका आइटमहरूले category_id आवश्यक पर्छ)
        $this->call(CategorySeeder::class);

        // ३. तेस्रो चरण: खानाका आइटमहरू सिड गर्ने
        $this->call(DishSeeder::class);

        // ४. चौथो चरण: अर्डरहरू सिड गर्ने (user_id र dish_id वैध हुनु पर्छ)
        $this->call(OrderSeeder::class);

        // ५. पाँचौं चरण: भूमिका र अनुमतिहरू सेटअप गर्ने
        $this->call(RolesPermissionsSeeder::class);

        // वैकल्पिक सिडरहरू (आवश्यक अनुसार अनकमेन्ट गर्नुहोस्)
        // $this->call(ReservationSeeder::class);
        // $this->call(TableSeeder::class);
    }
}