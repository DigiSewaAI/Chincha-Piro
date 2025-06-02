<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Menu; // ✅ Dish → Menu
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
        // Random menu छान्नुहोस्
        $menu = Menu::inRandomOrder()->first();

        // यदि कुनै menu छैन भने, factory बाट बनाउनुहोस्
        if (!$menu) {
            $menu = Menu::factory()->create();
        }

        return [
            'menu_id' => $menu->id, // ✅ dish_id → menu_id
            'unit_price' => $menu->price, // ✅ Dish मा price फिल्ड हुनुपर्छ
            'quantity' => $this->faker->numberBetween(1, 5),
            'total_price' => $menu->price * $this->faker->numberBetween(1, 5), // ✅ total_price समावेश गरिएको
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure()
    {
        return $this->afterMaking(function (OrderItem $orderItem) {
            // 'total_price' लाई 'unit_price' × 'quantity' बाट गणना गर्ने
            $orderItem->total_price = $orderItem->unit_price * $orderItem->quantity;
        })->afterCreating(function (OrderItem $orderItem) {
            // आवश्यक भएमा, अतिरिक्त काम गर्नुहोस्
        });
    }
}
