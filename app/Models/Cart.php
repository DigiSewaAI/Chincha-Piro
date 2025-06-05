<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory, SoftDeletes;

    // ✅ Mass assignment समर्थनका लागि
    protected $fillable = ['user_id', 'session_id'];

    // 🛒 कार्ट आइटम सम्बन्ध
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // 👤 प्रयोगकर्ता सम्बन्ध
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // 📦 कार्टको कुल मूल्य गणना (उप-कुल)
    public function getSubTotalAttribute()
    {
        return $this->items->sum(fn($item) => $item->price * $item->quantity);
    }

    // 💰 कार्टको कुल मूल्य (ट्याक्स सहित)
    public function getTotalAttribute()
    {
        return $this->sub_total * 1.13; // 13% VAT
    }

    // 📊 कार्टमा आइटमहरूको संख्या
    public function getItemCountAttribute()
    {
        return $this->items->sum('quantity');
    }

    // 🔐 कार्टलाई प्रयोगकर्ता वा सेशनबाट प्राप्त गर्ने
    public static function getCartForUserOrSession()
    {
        if (auth()->check()) {
            return static::firstOrCreate(['user_id' => auth()->id()]);
        }

        $sessionId = session()->getId();
        return static::firstOrCreate(['session_id' => $sessionId]);
    }

    // 🧮 कार्टको कुल गणना (ट्याक्स सहित)
    public function calculateTotal()
    {
        return $this->items->sum(fn($item) => $item->price * $item->quantity) * 1.13;
    }

    // 🚫 कार्टमा आइटमहरू छन् कि भने जाँच गर्ने
    public function isEmpty()
    {
        return $this->items->isEmpty();
    }
}
