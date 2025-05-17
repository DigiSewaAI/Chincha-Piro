<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'status',
        'changed_by',
        'notes',
        'ip_address',
        'user_agent'
    ];

    /**
     * Get the order that owns the status history.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Get the user who changed the status.
     */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Create initial status history for a new order.
     */
    public static function createInitialHistory(Order $order): StatusHistory
    {
        return $order->statusHistories()->create([
            'status' => 'pending',
            'changed_by' => $order->user_id,
            'notes' => 'Initial order creation',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}
