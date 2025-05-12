<?php
namespace App\Helpers;

class StatusHelper {
    public static function getStatusClass($status) {
        return match($status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'processing' => 'bg-purple-100 text-purple-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public static function getStatusText($status) {
        return match($status) {
            'pending' => 'पुष्टि हुन बाँकी',
            'confirmed' => 'पुष्टि भएको',
            'processing' => 'तयारीमा',
            'completed' => 'पुरा भएको',
            'cancelled' => 'रद्द भएको',
            default => 'अज्ञात',
        };
    }
}
