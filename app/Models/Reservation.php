<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reservations';

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
        'cancellation_reason', // ✅ Added missing field
        'cancelled_at',          // ✅ Added missing field
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
     * Boot method to add model-level validation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reservation) {
            $validator = Validator::make($reservation->getAttributes(), [
                'contact_number' => [
                    'required',
                    'string',
                    'regex:/^(98|97|96)\d{8}$/'
                ],
                'reservation_time' => 'required|date|after:now',
                'guests' => 'required|integer|min:1|max:100',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        });
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

    /**
     * Check if status transition is valid.
     *
     * @param string $newStatus
     * @return bool
     */
    public function isValidStatusTransition(string $newStatus): bool
    {
        $validTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['cancelled'],
            'cancelled' => [],
        ];

        return isset($validTransitions[$this->status])
            && in_array($newStatus, $validTransitions[$this->status]);
    }

    /**
     * Cancel the reservation with optional reason.
     *
     * @param string|null $reason
     * @return bool
     */
    public function cancel(?string $reason = null): bool
    {
        if (!$this->isValidStatusTransition('cancelled')) {
            return false;
        }

        return $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_at' => now(),
        ]);
    }

    /**
     * Confirm the reservation.
     *
     * @return bool
     */
    public function confirm(): bool
    {
        if (!$this->isValidStatusTransition('confirmed')) {
            return false;
        }

        return $this->update(['status' => 'confirmed']);
    }

    /**
     * Get formatted reservation time in Nepali.
     *
     * @return string
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->reservation_time
            ->setLocale('ne')
            ->isoFormat('YYYY-MM-DD HH:mm');
    }

    /**
     * Scope to get only active reservations.
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'cancelled')->whereNull('deleted_at');
    }

    /**
     * Scope to get reservations for a specific date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('reservation_time', [$startDate, $endDate]);
    }
}
