<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'cart_id',
        'menu_id',
        'quantity',
        'price',
        'extras',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'quantity' => 'integer',
        'price'    => 'float',
        'extras'   => 'array', // JSON data, e.g., toppings, notes
    ];

    /**
     * Relationships
     */

    // Cart relationship
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Menu item relationship
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Accessors
     */

    // Total price of this cart item (quantity × unit price)
    public function getTotalPriceAttribute(): float
    {
        return $this->quantity * $this->price;
    }
}
