<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'phone',
        'address',
        'special_instructions',
        'preferred_delivery_time',
        'payment_method',
        'status',
        'total_price',
        'is_public',
        'is_paid',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i',
        'updated_at' => 'datetime:Y-m-d H:i',
        'preferred_delivery_time' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    // ✅ User सँगको सम्बन्ध
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ✅ OrderItems सँगको सम्बन्ध (मेनुलाई सही रूपमा लोड गर्ने)
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class)->with('menu'); // ✅ 'menu' प्रयोग
    }

    // ✅ Menus सँगको सम्बन्ध (पहिले dishes() थियो, अब menus())
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'order_items') // ✅ order_items प्रयोग
                    ->withPivot(['quantity', 'unit_price', 'total_price']) // Pivot डेटा
                    ->withTimestamps(); // created_at/updated_at
    }

    // ✅ Status History सँगको सम्बन्ध
    public function statusHistories(): HasMany
    {
        return $this->hasMany(StatusHistory::class)->orderByDesc('created_at');
    }

    // ✅ Public Orders स्कोप
    public function scopePublicOrders(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }

    // ✅ Order Filter स्कोप
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['status'] ?? null, fn($q) => $q->where('status', $filters['status']))
            ->when($filters['search'] ?? null, function ($q) use ($filters) {
                return $q->whereHas('user', fn($query) =>
                    $query->where('name', 'like', "%{$filters['search']}%")
                          ->orWhere('email', 'like', "%{$filters['search']}%")
                )->orWhere('id', $filters['search']);
            });
    }

    // ✅ Status लाई Color मा देखाउने Attribute
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300',
            'confirmed' => 'bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300',
            'preparing' => 'bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-300',
            'on_delivery' => 'bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-300',
            'completed' => 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300',
            'cancelled' => 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300',
            default => 'bg-gray-100 text-gray-600 dark:bg-gray-900 dark:text-gray-300',
        };
    }

    // ✅ Payment Method लाई Label मा देखाउने Attribute
    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cash' => 'नगद',
            'esewa' => 'ईसेवा',
            'khalti' => 'खल्ती',
            'card' => 'कार्ड',
            default => ucfirst($this->payment_method),
        };
    }
}
