<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ✅ आवश्यकता अनुसार सही क्याटेगरी थपिएको
        $categories = [
            ['name' => 'खाना', 'description' => 'परम्परागत नेपाली खाना'],
            ['name' => 'नास्ता', 'description' => 'आहारका लागि हल्का नास्ता'],
            ['name' => 'मिठाई', 'description' => 'घरेलु र पारम्परिक मिठाईहरू'],
            ['name' => 'पेय', 'description' => 'स्वादिष्ट पेय वस्तुहरू']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
