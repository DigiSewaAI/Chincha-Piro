<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'description',
        'type',
        'file_path',
    ];

    // Optional: Accessor to detect if the item is a photo
    public function isPhoto(): bool
    {
        return $this->type === 'photo';
    }

    // Optional: Accessor to detect if the item is a video
    public function isVideo(): bool
    {
        return $this->type === 'video';
    }
}
