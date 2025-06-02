<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    // ✅ Fillable fields (dish_id → menu_id)
    protected $fillable = [
        'order_id',
        'menu_id',      // ✅ dish_id बदलेर menu_id प्रयोग गरिएको
        'quantity',
        'unit_price',
        'total_price'
    ];

    // ✅ Relationship to Order
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // ✅ Relationship to Menu (पहिले dish() थियो, अब menu())
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
