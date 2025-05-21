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
        'image_path',
        'is_active',
        'featured'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'featured' => 'boolean'
    ];

    /**
     * Get the URL for the file (photo or video)
     */
    public function getFileUrlAttribute()
    {
        if ($this->isPhoto()) {
            return asset('storage/' . $this->image_path);
        }

        if ($this->isLocalVideo()) {
            return asset('storage/' . $this->image_path);
        }

        if ($this->isExternalVideo()) {
            return $this->image_path;
        }

        return null;
    }

    /**
     * Get the photo URL (alias for file_url)
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->isPhoto()
            ? asset('storage/' . $this->image_path)
            : null;
    }

    /**
     * Get the video URL (alias for file_url)
     */
    public function getVideoUrlAttribute(): ?string
    {
        if ($this->isLocalVideo()) {
            return asset('storage/' . $this->image_path);
        }

        if ($this->isExternalVideo()) {
            return $this->image_path;
        }

        return null;
    }

    /**
     * Check if the item is a photo
     */
    public function isPhoto(): bool
    {
        return $this->type === 'photo';
    }

    /**
     * Check if the item is a local video
     */
    public function isLocalVideo(): bool
    {
        return $this->type === 'local_video';
    }

    /**
     * Check if the item is an external video
     */
    public function isExternalVideo(): bool
    {
        return $this->type === 'external_video';
    }

    /**
     * Check if the item is a video (local or external)
     */
    public function isVideo(): bool
    {
        return in_array($this->type, ['local_video', 'external_video']);
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
            'class' => $this->is_active
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
    public static function getTypeOptions()
{
    return [
        'photo' => 'फोटो',
        'video' => 'बाह्य भिडियो (YouTube)',
        'local_video' => 'स्थानीय भिडियो' // ✅ थप्नुहोस्
    ];
}
}
