<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // १. Admin User (यदि छैन भने मात्र सिर्जना)
        User::updateOrCreate(
            ['email' => 'parasharregmi@gmail.com'],
            [
                'name' => 'Parashar Regmi',
                'password' => Hash::make('himalayan'),
                'email_verified_at' => now(), // Email Verified (लगइनका लागि आवश्यक)
                'role' => 'admin',    // ✅ role column थपिएको
                'is_active' => true,  // ✅ is_active column थपिएको
            ]
        );

        // २. Dummy Users (फ्याक्ट्री प्रयोग गरेर)
        \App\Models\User::factory(5)->create([
            'role' => 'user',       // ✅ नियमित यूजरहरूका लागि role
            'is_active' => true,    // ✅ सबै डमी यूजरहरूलाई सक्रिय बनाउनुहोस्
        ]);
    }
}
