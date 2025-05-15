<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\User;
use App\Models\Dish;
use App\Models\StatusHistory;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',              // Related user
        'customer_name',        // Customer name
        'phone',                // Phone number
        'address',              // Address
        'special_instructions', // Special instructions
        'preferred_delivery_time', // Preferred delivery time
        'status',               // Order status (in English)
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
        'preferred_delivery_time' => 'datetime',
    ];

    /**
     * Relationship to the User model.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Many-to-many relationship to the Dish model through order_items pivot table.
     */
    public function dishes(): BelongsToMany
    {
        return $this->belongsToMany(Dish::class, 'order_items')
            ->withPivot('quantity', 'price', 'special_instructions')
            ->withTimestamps();
    }

    /**
     * Relationship to the StatusHistory model.
     */
    public function statusHistories(): HasMany
    {
        return $this->hasMany(StatusHistory::class);
    }

    /**
     * Accessor for status color class.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300',
            'confirmed' => 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300',
            'processing' => 'bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-300',
            'completed' => 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300',
            'cancelled' => 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300',
            default => 'bg-gray-100 text-gray-600 dark:bg-gray-900 dark:text-gray-300',
        };
    }

    /**
     * Calculate total order price including all items
     */
    public function getTotalPriceAttribute(): float
    {
        return $this->dishes->sum(function($dish) {
            return $dish->pivot->quantity * $dish->pivot->price;
        });
    }

    /**
     * Get all items for this order
     */
    public function items()
    {
        return $this->hasMany(\App\Models\OrderItem::class);
    }
}