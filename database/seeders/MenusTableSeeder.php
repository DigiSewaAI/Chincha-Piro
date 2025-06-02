<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Static menu items
        $menus = [
            [
                'name' => 'म:म:',
                'price' => 120.00,
                'image' => 'momo.jpg',
                'description' => 'परम्परागत नेपाली म:म:',
                'category_id' => 1, // 'खाना'
                'is_featured' => false
            ],
            [
                'name' => 'बर्गर',
                'price' => 250.00,
                'image' => 'burger.jpg',
                'description' => 'अमेरिकन स्टाइल बर्गर',
                'category_id' => 2, // 'नास्ता'
                'is_featured' => false
            ],
            [
                'name' => 'चाउमीन',
                'price' => 150.00,
                'image' => 'chowmein.jpg',
                'description' => 'चिनियाँ स्वाद चाउमीन',
                'category_id' => 1, // 'खाना'
                'is_featured' => false
            ],
            [
                'name' => 'सेकुवा',
                'price' => 350.00,
                'image' => 'sekuwa.jpg',
                'description' => 'माछाको सेकुवा',
                'category_id' => 1, // 'खाना'
                'is_featured' => false
            ],
            [
                'name' => 'किचनी चावल',
                'price' => 180.00,
                'image' => 'kichdi.jpg',
                'description' => 'खानाको लागि साधारण चावलको बनावट',
                'category_id' => 1, // 'खाना'
                'is_featured' => false
            ],
            [
                'name' => 'आलु ताकाती',
                'price' => 100.00,
                'image' => 'alu_tukuti.jpg',
                'description' => 'खानाको साइड डिश',
                'category_id' => 1, // 'खाना'
                'is_featured' => false
            ],
            [
                'name' => 'फ्राइड राइस',
                'price' => 160.00,
                'image' => 'fried_rice.jpg',
                'description' => 'चिनियाँ शैलीको फ्राइड राइस',
                'category_id' => 1, // 'खाना'
                'is_featured' => false
            ],
            [
                'name' => 'स्मूदी',
                'price' => 140.00,
                'image' => 'smoothie.jpg',
                'description' => 'फलको स्मूदी',
                'category_id' => 4, // 'पेय'
                'is_featured' => true // Featured मान
            ],
            [
                'name' => 'लस्सी',
                'price' => 120.00,
                'image' => 'lassi.jpg',
                'description' => 'दही आधारित पेय',
                'category_id' => 4, // 'पेय'
                'is_featured' => false
            ],
            [
                'name' => 'मिठाई',
                'price' => 80.00,
                'image' => 'sweets.jpg',
                'description' => 'घरेलु नेपाली मिठाई',
                'category_id' => 3, // 'मिठाई'
                'is_featured' => false
            ],
        ];

        // 2. Insert static data
        foreach ($menus as $menuData) {
            Menu::create($menuData);
        }

        // 3. Generate additional random menus (optional)
        // Menu::factory(10)->create();
    }
}
