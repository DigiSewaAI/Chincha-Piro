<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Menu;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition()
    {
        // नेपाली खाना, रक्सी, र पेयको सूची
        $nepaliFoods = ['Chicken Momo', 'Buff Momo', 'Vegetable Momo', 'Chicken Chowmein', 'Buff Chowmein', 'Vegetable Chowmein', 'Sizzler', 'Thakali Bhoj', 'Dal Bhat', 'Fry Rice'];
        $liquors = ['Whiskey', 'Rum', 'Vodka', 'Beer', 'Wine', 'Arrack', 'Local Rum', 'Imported Beer', 'Cheap Vodka', 'Budget Whiskey'];
        $drinks = ['Black Tea', 'Milk Tea', 'Coffee', 'Soft Drink', 'Juice', 'Mineral Water'];

        // मूल्यको लागि यादृच्छिक सीमा
        $priceRanges = [
            'food' => [50, 500],     // नेपाली खानाको मूल्य
            'liquor' => [300, 10000], // लिकरको मूल्य (अब ३०० देखि)
            'drink' => [30, 200]      // पेयको मूल्य
        ];

        // यादृच्छिक रूपमा प्रकार चयन गर्नुहोस्
        $type = $this->faker->randomElement(['food', 'liquor', 'drink']);

        // नाम र मूल्य जनरेट गर्नुहोस्
        switch ($type) {
            case 'food':
                $name = $this->faker->randomElement($nepaliFoods);
                $price = $this->faker->randomFloat(2, ...$priceRanges['food']);
                break;
            case 'liquor':
                $name = $this->faker->randomElement($liquors);
                $price = $this->faker->randomFloat(2, ...$priceRanges['liquor']);
                break;
            default:
                $name = $this->faker->randomElement($drinks);
                $price = $this->faker->randomFloat(2, ...$priceRanges['drink']);
        }

        return [
            'name' => $name,
            'price' => $price,
            'stock' => $this->faker->numberBetween(5, 50), // स्टक 5-50
        ];
    }
}
