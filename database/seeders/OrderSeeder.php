<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Dish;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $dish = Dish::first();

        // यदि डिश नै भेटिएन भने एउटा डिश पहिले बनाउनुहोस्
        if (!$dish) {
            $dish = Dish::create([
                'name' => 'मोमो',
                'price' => 150,
                // अरू आवश्यक field हरू थप्नुहोस्
            ]);
        }

        Order::create([
            'dish_id' => $dish->id,
            'quantity' => 2,
            'total_price' => $dish->price * 2,
            'customer_name' => 'राम बस्नेत',
            'phone' => '9800000000',
            'address' => 'काठमाडौँ',
            'status' => 'pending'
        ]);
    }
}
