<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dish extends Model
{
    protected $fillable = ['name', 'price', 'description', 'image'];

    // Relation to Orders
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Optional: Add formatted price accessor
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2) . ' रू';
    }
}
