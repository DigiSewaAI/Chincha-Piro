<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory, SoftDeletes;

    // ✅ Mass assignment समर्थनका लागि
    protected $fillable = ['user_id', 'session_id'];

    /**
     * 🛒 कार्ट आइटम सम्बन्ध
     * CartItem टेबलमा 'cart_id' फिल्ड अवश्य पर्दछ
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * 👤 प्रयोगकर्ता सम्बन्ध
     * Cart टेबलमा 'user_id' फिल्ड अवश्य पर्दछ
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * 📦 कार्टको कुल मूल्य गणना (उदाहरण: 13% VAT सँग)
     */
    public function getSubTotalAttribute()
    {
        return $this->items->sum(fn($item) => $item->price * $item->quantity);
    }

    public function getTotalAttribute()
    {
        return $this->sub_total * 1.13; // 13% VAT
    }

    /**
     * 📊 कार्टमा आइटमहरूको संख्या
     */
    public function getItemCountAttribute()
    {
        return $this->items->sum('quantity');
    }

    /**
     * 🔐 कार्टलाई प्रयोगकर्ता वा सेशनबाट प्राप्त गर्ने
     */
    public static function getCartForUserOrSession()
    {
        if (auth()->check()) {
            return static::firstOrCreate(['user_id' => auth()->id()]);
        }

        $sessionId = session()->getId();
        return static::firstOrCreate(['session_id' => $sessionId]);
    }

    /**
     * 🧮 कार्टको कुल मूल्य गणना
     */
    public function calculateTotal(): float
    {
        return $this->items->sum(fn($item) => $item->price * $item->quantity) * 1.13;
    }

    /**
     * 🚫 कार्टमा आइटमहरू छन्/छैन भने जाँच गर्ने
     */
    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }
}
