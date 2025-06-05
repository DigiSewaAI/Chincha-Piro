<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    // ✅ Mass assignment समर्थनका लागि
    protected $fillable = ['user_id', 'session_id'];

    // 🛒 कार्ट आइटम सम्बन्ध
    public function items()
    {
        return $this->hasMany(\App\Models\CartItem::class);
    }

    // 👤 प्रयोगकर्ता सम्बन्ध
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
