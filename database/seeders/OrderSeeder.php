<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\Menu; // ✅ Dish → Menu

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. User नभएमा बनाउने
        $user = User::firstOrCreate(
            ['email' => 'default@example.com'],
            [
                'name' => 'Default User',
                'password' => bcrypt('password'),
                'role' => 'user',
                'is_active' => true,
            ]
        );

        // 2. Menu नभएमा रोक्ने
        if (Menu::count() === 0) {
            $this->command->error('❌ पहिले Menu सिड गर्नुहोस्!');
            return;
        }

        // 3. 20 ओटा ऑर्डर क्रिएट गर्ने
        Order::factory(20)->create(['user_id' => $user->id])
            ->each(function ($order) {
                // 4. 1-5 ओटा मेनु छान्ने
                $menus = Menu::inRandomOrder()->take(rand(1, 5))->get();
                $totalPrice = 0; // total_price को लागि variable

                // 5. प्रत्येक मेनुलाई जोड्ने
                foreach ($menus as $menu) {
                    $quantity = rand(1, 3);
                    $unitPrice = $menu->price;
                    $itemTotal = $unitPrice * $quantity;

                    $order->menus()->attach($menu->id, [
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,   // ✅ price को सट्टा unit_price प्रयोग गरिएको
                        'total_price' => $itemTotal,  // ✅ pivot table मा total_price प्रयोग गरिएको
                    ]);

                    $totalPrice += $itemTotal;
                }

                // 6. ऑर्डर अपडेट गर्ने (total_price प्रयोग गरिएको)
                $order->update([
                    'total_price' => $totalPrice, // ✅ सही column: total_price
                    'status' => 'pending' // confirmed, cancelled, etc.
                ]);
            });

        $this->command->info('✅ 20 ऑर्डर सिड गरियो!');
    }
}
