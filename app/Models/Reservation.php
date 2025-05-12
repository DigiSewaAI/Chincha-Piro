<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'reservation_time',
        'guests',
        'contact_number',
        'special_request',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'reservation_time' => 'datetime',
        'guests' => 'integer',
        'status' => 'string',
    ];

    /**
     * Define relationship to User model.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get Nepali translation of status.
     *
     * @return string
     */
    public function getStatusNepaliAttribute(): string
    {
        return match($this->status) {
            'confirmed' => 'पुष्टि भएको',
            'cancelled' => 'रद्द भएको',
            default => 'पुष्टि हुन बाँकी',
        };
    }
}
