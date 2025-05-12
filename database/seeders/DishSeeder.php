<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dish;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Static dish data (mapped to categories seeded earlier)
        $dishes = [
            [
                'name' => 'म:म:',
                'price' => 120.00,
                'image' => 'momo.jpg',
                'description' => 'परम्परागत नेपाली म:म:',
                'spice_level' => 4,
                'category_id' => 1, // 'खाना'
                'is_available' => true
            ],
            [
                'name' => 'बर्गर',
                'price' => 250.00,
                'image' => 'burger.jpg',
                'description' => 'अमेरिकन स्टाइल बर्गर',
                'spice_level' => 1,
                'category_id' => 2, // 'नास्ता'
                'is_available' => true
            ],
            [
                'name' => 'चाउमीन',
                'price' => 150.00,
                'image' => 'chowmein.jpg',
                'description' => 'चिनियाँ स्वाद चाउमीन',
                'spice_level' => 3,
                'category_id' => 1, // 'खाना'
                'is_available' => true
            ],
            [
                'name' => 'सेकुवा',
                'price' => 350.00,
                'image' => 'sekuwa.jpg',
                'description' => 'माछाको सेकुवा',
                'spice_level' => 5,
                'category_id' => 1, // 'खाना'
                'is_available' => true
            ],
            [
                'name' => 'किचनी चावल',
                'price' => 180.00,
                'image' => 'kichdi.jpg',
                'description' => 'खानाको लागि साधारण चावलको बनावट',
                'spice_level' => 2,
                'category_id' => 1, // 'खाना'
                'is_available' => true
            ],
            [
                'name' => 'आलु ताकाती',
                'price' => 100.00,
                'image' => 'alu_tukuti.jpg',
                'description' => 'खानाको साइड डिश',
                'spice_level' => 3,
                'category_id' => 1, // 'खाना'
                'is_available' => true
            ],
            [
                'name' => 'फ्राइड राइस',
                'price' => 160.00,
                'image' => 'fried_rice.jpg',
                'description' => 'चिनियाँ शैलीको फ्राइड राइस',
                'spice_level' => 2,
                'category_id' => 1, // 'खाना'
                'is_available' => true
            ],
            [
                'name' => 'स्मूदी',
                'price' => 140.00,
                'image' => 'smoothie.jpg',
                'description' => 'फलको स्मूदी',
                'spice_level' => 1,
                'category_id' => 4, // 'पेय'
                'is_available' => true
            ],
            [
                'name' => 'लस्सी',
                'price' => 120.00,
                'image' => 'lassi.jpg',
                'description' => 'दही आधारित पेय',
                'spice_level' => 1,
                'category_id' => 4, // 'पेय'
                'is_available' => true
            ],
            [
                'name' => 'मिठाई',
                'price' => 80.00,
                'image' => 'sweets.jpg',
                'description' => 'घरेलु नेपाली मिठाई',
                'spice_level' => 1,
                'category_id' => 3, // 'मिठाई'
                'is_available' => true
            ],
        ];

        // 2. Insert static data
        foreach ($dishes as $dishData) {
            Dish::create($dishData);
        }

        // 3. Generate additional random dishes (optional)
        // Dish::factory(10)->create();
    }
}
