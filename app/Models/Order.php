<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;  // User मोडेलको लागि namespace
use App\Models\Dish;  // Dish मोडेलको लागि namespace

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',         // सम्बन्धित User ID
        'dish_id',
        'quantity',
        'total_price',
        'customer_name',
        'phone',
        'address',
        'status'
    ];

    /**
     * Casts for attributes.
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
    ];

    /**
     * Relation to Dish model.
     */
    public function dish(): BelongsTo
    {
        return $this->belongsTo(Dish::class);
    }

    /**
     * Relation to User model.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Status color attribute accessor.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'पूरा भयो' => 'bg-green-100 text-green-600',
            'पुष्टि हुन बाँकी' => 'bg-yellow-100 text-yellow-600',
            default => 'bg-gray-100 text-gray-600',
        };
    }
}
