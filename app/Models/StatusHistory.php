<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    protected $fillable = [
        'status',
        'changed_by',
        'notes',
        'order_id' // आवश्यक भएमा
    ];

    // Order सँग सम्बन्ध
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // User सँग सम्बन्ध
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
