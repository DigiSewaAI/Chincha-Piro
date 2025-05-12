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
        $categories = [
            ['name' => 'नेपाली व्यञ्जन', 'description' => 'परम्परागत नेपाली खाना'],
            ['name' => 'चिनियाँ व्यञ्जन', 'description' => 'चिनियाँ स्वाद'],
            ['name' => 'फास्ट फूड', 'description' => 'अन्तर्राष्ट्रिय फास्ट फूड']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
