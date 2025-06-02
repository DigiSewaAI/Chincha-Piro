<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Category; // ✅ Category मोडल आवश्यक छ

class Menu extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category_id',
        'is_featured' // ✅ is_featured फिल्ड थपिएको
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'is_featured' => 'boolean', // ✅ is_featured लाई boolean मा कास्ट गर्ने
        'price' => 'float' // ✅ मूल्यलाई सँधै float मा कास्ट गर्ने
    ];

    /**
     * Get the category this menu belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class); // ✅ category_id → Category::class
    }

    /**
     * Get the full image URL.
     */
    public function getImageUrlAttribute()
    {
        // ✅ डिफल्ट छवि थपिएको (यदि आवश्यक भए)
        return $this->image
            ? Storage::url($this->image)
            : asset('images/menu-default.jpg'); // public/images/menu-default.jpg हुनुपर्छ
    }
}
