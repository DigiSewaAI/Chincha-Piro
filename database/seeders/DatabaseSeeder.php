<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Foreign Key Constraints हटाउने
        Schema::disableForeignKeyConstraints();

        // पुराना डाटा मेटाउने (dishes हटाएर menus प्रयोग गरिएको)
        DB::table('users')->truncate();
        DB::table('categories')->truncate();
        DB::table('menus')->truncate();  // dishes → menus
        DB::table('orders')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();

        // Foreign Key Constraints पुनः सक्रिय गर्ने
        Schema::enableForeignKeyConstraints();

        // १. पहिलो चरण: प्रयोगकर्ता सिड गर्ने
        $this->call(UserSeeder::class);

        // २. दोस्रो चरण: श्रेणीहरू सिड गर्ने
        $this->call(CategorySeeder::class);

        // ३. तेस्रो चरण: मेनु आइटमहरू सिड गर्ने (DishSeeder हटाएर MenuSeeder प्रयोग)
        $this->call(MenusTableSeeder::class);  // DishSeeder → MenusTableSeeder

        // ४. चौथो चरण: अर्डरहरू सिड गर्ने
        $this->call(OrderSeeder::class);

        // ५. पाँचौं चरण: भूमिका र अनुमतिहरू सेटअप गर्ने
        $this->call(RolesPermissionsSeeder::class);

        // वैकल्पिक सिडरहरू (आवश्यक अनुसार अनकमेन्ट गर्नुहोस्)
        // $this->call(ReservationSeeder::class);
        // $this->call(TableSeeder::class);
    }
}
