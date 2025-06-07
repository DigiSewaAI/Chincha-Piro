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
        'image_path',
        'video_url',
        'is_active',
        'featured'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'featured' => 'boolean'
    ];

    public function getFileUrlAttribute()
    {
        if ($this->isPhoto() || $this->isLocalVideo()) {
            return asset('storage/' . $this->image_path);
        }

        if ($this->isExternalVideo()) {
            return $this->video_url;
        }

        return null;
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->isPhoto()
            ? asset('storage/' . $this->image_path)
            : null;
    }

    public function getVideoUrlAttribute(): ?string
    {
        if ($this->isLocalVideo()) {
            return asset('storage/' . $this->image_path);
        }

        if ($this->isExternalVideo()) {
            return $this->attributes['video_url'] ?? null;
        }

        return null;
    }

    /**
     * 🔁 Get the embed-friendly YouTube video URL
     */
    public function getVideoEmbedUrlAttribute(): ?string
    {
        if (! $this->isExternalVideo() || empty($this->video_url)) {
            return null;
        }

        $url = $this->video_url;

        // Extract YouTube Video ID
        preg_match(
            '%(?:youtu\.be/|youtube\.com/(?:embed/|v/|watch\?v=|shorts/|watch\?.+&v=))([\w-]{11})%',
            $url,
            $matches
        );

        $videoId = $matches[1] ?? null;

        return $videoId ? 'https://www.youtube.com/embed/'  . $videoId : null;
    }

    public function isPhoto(): bool
    {
        return $this->type === 'photo';
    }

    public function isLocalVideo(): bool
    {
        return $this->type === 'local_video';
    }

    public function isExternalVideo(): bool
    {
        return $this->type === 'external_video';
    }

    public function isVideo(): bool
    {
        return in_array($this->type, ['local_video', 'external_video']);
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::getCategoryOptions()[$this->category] ?? $this->category;
    }

    public function getTypeLabelAttribute(): string
    {
        return self::getTypeOptions()[$this->type] ?? $this->type;
    }

    public function getStatusBadgeAttribute(): array
    {
        return [
            'class' => $this->is_active ? 'badge bg-success' : 'badge bg-danger',
            'text' => $this->is_active ? 'सक्रिय' : 'निष्क्रिय'
        ];
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public static function getCategoryOptions(): array
    {
        return [
            'food' => 'भोजन',
            'restaurant' => 'रेस्टुरेन्ट',
            'event' => 'घटना',
            'other' => 'अन्य'
        ];
    }

    public static function getTypeOptions(): array
    {
        return [
            'photo' => 'फोटो',
            'external_video' => 'बाह्य भिडियो (YouTube)',
            'local_video' => 'स्थानीय भिडियो'
        ];
    }
}
