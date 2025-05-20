<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Dish;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // Random dish छान्नुहोस्
        $dish = Dish::inRandomOrder()->first();

        // यदि कुनै dish छैन भने, factory बाट बनाउनुहोस्
        if (!$dish) {
            $dish = Dish::factory()->create();
        }

        return [
            'dish_id' => $dish->id,
            'unit_price' => $dish->price, // Dish मा price फिल्ड हुनुपर्छ
            'quantity' => $this->faker->numberBetween(1, 5),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure()
    {
        return $this->afterMaking(function (OrderItem $orderItem) {
            // 'price' लाई 'unit_price' × 'quantity' बाट गणना गर्ने
            $orderItem->price = $orderItem->unit_price * $orderItem->quantity;
        })->afterCreating(function (OrderItem $orderItem) {
            // आवश्यक भएमा, अतिरिक्त काम गर्नुहोस्
        });
    }
}
