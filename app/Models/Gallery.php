<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'category',
        'description',
        'type',
<<<<<<< HEAD
        'image_path',  // सही कलम नाम (तपाईंले माइग्रेशनमा जस्तो प्रयोग गर्नुभएको छ)
        'is_active',
        'featured'
=======
        'image_path',
>>>>>>> bf0c141ad1cd9b5312caa672c6a6e68455c88f46
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',  // 'status' को सट्टामा 'is_active'
        'featured' => 'boolean'
    ];

    /**
     * Get the URL for the file (photo or video)
     */
    public function getFileUrlAttribute()
    {
        return $this->isPhoto()
            ? asset('storage/' . $this->image_path)  // 'file_path' को सट्टामा 'image_path'
            : $this->image_path;
    }

    /**
     * Get the photo URL (alias for file_url)
     */
    public function getPhotoUrlAttribute()
    {
        return $this->isPhoto() ? $this->file_url : null;
    }

    /**
     * Get the video URL (alias for file_url)
     */
    public function getVideoUrlAttribute()
    {
        return $this->isVideo() ? $this->file_url : null;
    }

    /**
     * Check if the item is a photo
     */
    public function isPhoto(): bool
    {
        return $this->type === 'photo';
    }

    /**
     * Check if the item is a video
     */
    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    /**
     * Get the category label in Nepali
     */
    public function getCategoryLabelAttribute(): string
    {
        return self::getCategoryOptions()[$this->category] ?? $this->category;
    }

    /**
     * Get the type label in Nepali
     */
    public function getTypeLabelAttribute(): string
    {
        return self::getTypeOptions()[$this->type] ?? $this->type;
    }

    /**
     * Get the status badge HTML attributes
     */
    public function getStatusBadgeAttribute(): array
    {
        return [
            'class' => $this->is_active  // 'status' को सट्टामा 'is_active'
                ? 'badge bg-success'
                : 'badge bg-danger',
            'text' => $this->is_active ? 'सक्रिय' : 'निष्क्रिय'
        ];
    }

    /**
     * Scope to filter by category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to filter by type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Static method to get category options in Nepali
     */
    public static function getCategoryOptions(): array
    {
        return [
            'food' => 'भोजन',
            'restaurant' => 'रेस्टुरेन्ट',
            'event' => 'घटना',
            'other' => 'अन्य'
        ];
    }

    /**
     * Static method to get type options in Nepali
     */
    public static function getTypeOptions(): array
    {
        return [
            'photo' => 'फोटो',
            'video' => 'भिडियो'
        ];
    }
}
