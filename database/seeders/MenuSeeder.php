<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // 30 वटा मेनु आइटम सिर्जना गर्नुहोस्
        Menu::factory(30)->create();
    }
}
