<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User; // Model Import

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    // Model Binding
    protected $model = User::class;

    // Shared password for seeded users
    protected static ?string $password;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(), // Email verified by default
            'password' => static::$password ??= Hash::make('password'), // Default password: 'password'
            'remember_token' => Str::random(10),
            'role' => 'user', // ✅ role column (डिफल्ट 'user')
            'is_active' => true, // ✅ is_active column (डिफल्ट true)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Set the 'is_active' status.
     */
    public function isActive(bool $isActive = true): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => $isActive,
        ]);
    }

    /**
     * Set the 'role' (e.g., 'admin', 'user').
     */
    public function role(string $role): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => $role,
        ]);
    }

    /**
     * Shortcut: Create an admin user.
     */
    public function admin(): static
    {
        return $this->role('admin'); // ✅ 'admin' role लाई सजिलो बनाउन
    }

    /**
     * Shortcut: Deactivate user.
     */
    public function inactive(): static
    {
        return $this->isActive(false); // ✅ is_active => false
    }
}
