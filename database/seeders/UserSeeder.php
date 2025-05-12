<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // १. Admin User
        User::updateOrCreate(
            ['email' => 'parasharregmi@gmail.com'],
            [
                'name' => 'Parashar Regmi',
                'password' => Hash::make('Himalayan@1981'),
                'email_verified_at' => now(),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // २. नियमित प्रयोगकर्ता
        User::factory()->count(5)->create([
            'role' => 'user',
            'is_active' => true,
        ]);
    }
}
