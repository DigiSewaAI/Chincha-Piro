<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dish extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'price', 'description', 'image'];

    // Relation to Orders
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
// Dish.php मोडेलमा
public function getImageUrlAttribute()
{
    return $this->image ? asset("storage/{$this->image}") : env('FALLBACK_IMAGE');
}
    // Optional: Add formatted price accessor
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2) . ' रू';
    }
}
