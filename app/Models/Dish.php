<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Category;
use App\Models\Order;

class Dish extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'name_nepali',
        'is_available',
        'price',
        'description',
        'image',
        'spice_level',
        'category_id', // ✅ Updated: Use `category_id` for BelongsTo relationship
    ];

    /**
     * Casts for attributes
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'spice_level' => 'integer',
    ];

    /**
     * Relationship: Dish belongs to a Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Accessor for dish image URL
     */
    public function getImageUrlAttribute(): string
    {
        if (!$this->image) return asset('images/default-dish.jpg');

        $publicPath = public_path("images/dishes/{$this->image}");
        if (File::exists($publicPath)) return asset("images/dishes/{$this->image}");

        return asset('images/default-dish.jpg');
    }

    /**
     * Formatted price (e.g., "रु 250.00")
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'रु ' . number_format($this->price, 2);
    }

    /**
     * Dish orders relationship
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Total revenue from completed orders
     */
    public function getTotalRevenueAttribute(): float
    {
        return $this->orders()
            ->where('status', 'completed')
            ->sum(fn ($order) => $order->quantity * $order->price);
    }

    /**
     * Tooltip text with localized info
     */
    public function getTooltipText(string $locale = 'ne'): string
    {
        return "{$this->name} - {$this->description} (मूल्य: {$this->formattedPrice})";
    }

    /**
     * Category-based CSS class
     */
    public function getCategoryClassAttribute(): string
    {
        return match(strtolower($this->category->name ?? '')) {
            'खाना' => 'badge badge-primary',
            'नास्ता' => 'badge badge-secondary',
            'मिठाई' => 'badge badge-accent',
            'पेय' => 'badge badge-info',
            default => 'badge badge-ghost',
        };
    }

    /**
     * Spice level-based CSS class
     */
    public function getSpiceLevelClassAttribute(): string
    {
        return match((int)$this->spice_level) {
            1 => 'text-green-600 bg-green-100',
            2 => 'text-green-600 bg-green-100',
            3 => 'text-yellow-600 bg-yellow-100',
            4 => 'text-red-600 bg-red-100',
            5 => 'text-red-800 bg-red-200',
            default => 'text-gray-600 bg-gray-100',
        };
    }
}
