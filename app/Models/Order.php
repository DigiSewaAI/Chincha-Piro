<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'amount', 'status'];

    // Status color accessor
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'पूरा भयो' => 'bg-green-100 text-green-600',
            'पुष्टि हुन बाँकी' => 'bg-yellow-100 text-yellow-600',
            default => 'bg-gray-100 text-gray-600',
        };
    }
}
