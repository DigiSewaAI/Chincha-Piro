<?php

namespace Database\Factories;

use App\Models\Dish;
use Illuminate\Database\Eloquent\Factories\Factory;

class DishFactory extends Factory
{
    protected $model = Dish::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word, // र्यान्डम शब्द (जस्तै: "खिचडी", "मो:मो:")
            'description' => $this->faker->sentence, // र्यान्डम वाक्य
            'price' => $this->faker->randomFloat(2, 50, 500), // 50–500 बीचको मूल्य
            'category' => $this->faker->randomElement(['खाना', 'नास्ता', 'मिठाई']), // नेपालीमा श्रेणी
        ];
    }
}
