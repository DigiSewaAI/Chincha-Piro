<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = ['user_id', 'dish_id', 'amount', 'status'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
    ];

    // Relation to Dish
    public function dish(): BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }

    // Status color accessor
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'पूरा भयो' => 'bg-green-100 text-green-600',
            'पुष्टि हुन बाँकी' => 'bg-yellow-100 text-yellow-600',
            default => 'bg-gray-100 text-gray-600',
        };
    }
}
