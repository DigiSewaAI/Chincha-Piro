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
        'food_id', // मेनुको सट्टा फूडको आईडी
        'quantity',
        'price',
        'extras', // JSON data (उदाहरण: toppings, notes)
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'quantity' => 'integer',
        'price'    => 'float',
        'extras'   => 'array', // JSON data को लागि
    ];

    /**
     * Relationships
     */

    // कार्ट सम्बन्ध
    public function cart()
    {
        return $this->belongsTo(\App\Models\Cart::class);
    }

    // फूड सम्बन्ध
    public function food()
    {
        return $this->belongsTo(\App\Models\Food::class);
    }

    /**
     * Accessors
     */

    // यस कार्ट आइटमको कुल मूल्य (मात्रा × एकाइ मूल्य)
    public function getTotalPriceAttribute(): float
    {
        return $this->quantity * $this->price;
    }
}
