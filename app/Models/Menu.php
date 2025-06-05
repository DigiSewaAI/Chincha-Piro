<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// 📦 आवश्यक मोडेलहरू आयात गर्नुहोस्
use App\Models\Category;
use App\Models\Order;
use App\Models\CartItem;
use App\Models\Cart;

use Illuminate\Support\Facades\Storage;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'category_id',
        'status',
        'is_featured',
    ];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_featured' => 'boolean',
        'price' => 'float',
        'stock' => 'integer',
        'status' => 'string',
    ];

    /**
     * Relationship: Menu belongs to a Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: Menu has many Orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * ✅ Relationship: Menu has many CartItems.
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * ✅ Relationship: Menu has many Carts through CartItems.
     */
    public function carts(): HasMany
    {
        return $this->hasManyThrough(Cart::class, CartItem::class);
    }

    /**
     * Accessor: Full image URL.
     *
     * @return string
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image);
        }

        // Default image if not found
        return asset('images/menu-default.jpg');
    }

    /**
     * Scope: Only featured menu items.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope: Filter by category ID.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $categoryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * 📉 Check if item is in stock
     */
    public function isInStock(int $quantity = 1): bool
    {
        return $this->stock >= $quantity;
    }

    /**
     * 🔒 Lock menu item for update
     */
    public function lockForUpdate()
    {
        return $this->lockForUpdate()->findOrFail($this->id);
    }

    /**
     * 📊 Get total quantity in cart
     */
    public function getCartQuantityAttribute(): int
    {
        return $this->cartItems->sum('quantity');
    }

    /**
     * 📈 Update stock safely
     */
    public function updateStock(int $quantity, bool $increment = true): bool
    {
        $this->lockForUpdate();
        if (!$this->isInStock($quantity) && !$increment) {
            return false;
        }

        $this->stock = $increment
            ? $this->stock + $quantity
            : $this->stock - $quantity;

        return $this->save();
    }

    /**
     * 🧮 Calculate total value of menu in cart
     */
    public function getCartValueAttribute(): float
    {
        return $this->cartItems->sum(fn($item) => $item->price * $item->quantity);
    }

    /**
     * 🎯 Get all carts containing this menu item
     */
    public function getActiveCarts()
    {
        return $this->carts()
            ->whereHas('items')
            ->with('user')
            ->get();
    }

    /**
     * 📦 Check if menu is available for purchase
     */
    public function isAvailable(int $quantity = 1): bool
    {
        return $this->status === 'active' && $this->isInStock($quantity);
    }
}
