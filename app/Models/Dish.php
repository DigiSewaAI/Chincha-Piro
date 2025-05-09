<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Dish extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'price',
        'description',
        'image',
        'spice_level',
        'category'
    ];

    /**
     * Accessor for dish image URL
     *
     * - Checks if image exists in public directory
     * - Falls back to default image if not found
     * - Uses asset() helper for correct URL generation
     *
     * @return string
     */
    public function getImageUrlAttribute(): string
    {
        // 1. डेटाबेसमा इमेज नाम अवस्थित छ कि जाँच गर्नुहोस्
        if ($this->image) {
            // 2. पब्लिक पथ निर्माण गर्नुहोस्
            $publicPath = public_path("images/dishes/{$this->image}");

            // 3. स्टोरेज पथ निर्माण गर्नुहोस् (यदि स्टोरेज डिस्क प्रयोग गरिएको छ भने)
            $storagePath = Storage::disk('public')->path("dishes/{$this->image}");

            // 4. फाइल अवस्थित छ कि जाँच गर्नुहोस् (पब्लिक वा स्टोरेज दुवैमा)
            if (file_exists($publicPath) || file_exists($storagePath)) {
                // पब्लिक पथको लागि एसेट URL
                if (file_exists($publicPath)) {
                    return asset("images/dishes/{$this->image}");
                }

                // स्टोरेज पथको लागि स्टोरेज URL
                return Storage::disk('public')->url("dishes/{$this->image}");
            }
        }

        // 5. डिफल्ट इमेज प्रयोग गर्नुहोस्
        return asset('images/default-dish.jpg');
    }

    /**
     * फर्मेटेड मूल्य (उदाहरण: "रु 250.00")
     *
     * @return string
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'रु ' . number_format($this->price, 2);
    }

    /**
     * Dish ले कतिवटा अर्डरहरू लिएको छ
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Dish ले कतिवटा रेटिङ्ग प्राप्त गरेको छ
     *
     * @return int
     */
    public function getTotalRatingsAttribute(): int
    {
        return $this->ratings->count();
    }

    /**
     * डिशको औसत रेटिङ्ग प्रदर्शन गर्ने
     *
     * @return float
     */
    public function getAverageRatingAttribute(): float
    {
        return round($this->ratings->avg('rating') ?? 0, 1);
    }

    /**
     * डिशको श्रेणी आधारित CSS क्लास
     *
     * @return string
     */
    public function getCategoryClassAttribute(): string
    {
        return match(strtolower($this->category)) {
            'खाना' => 'badge badge-primary',
            'नास्ता' => 'badge badge-secondary',
            'मिठाई' => 'badge badge-accent',
            'पेय' => 'badge badge-info',
            default => 'badge badge-ghost',
        };
    }

    /**
     * स्पाइस लेवलको लागि CSS क्लास
     *
     * @return string
     */
    public function getSpiceLevelClassAttribute(): string
    {
        return match(strtolower($this->spice_level)) {
            'मध्यम' => 'text-yellow-600 bg-yellow-100',
            'तीव्र' => 'text-red-600 bg-red-100',
            'अति तीव्र' => 'text-red-800 bg-red-200',
            default => 'text-green-600 bg-green-100',
        };
    }

    /**
     * डिशको लोकेलाइज्ड विवरण
     *
     * @param string $locale
     * @return string
     */
    public function getLocalizedDescription(string $locale = 'ne'): string
    {
        // यहाँ तपाईंको अनुवाद लोजिक थप्नुहोस्
        // उदाहरणका लागि: return trans("dishes.{$this->id}.description", [], $locale);
        return $this->description; // सादा विवरण फर्काउँछ
    }

    /**
     * डिशको लोकेलाइज्ड नाम
     *
     * @param string $locale
     * @return string
     */
    public function getLocalizedName(string $locale = 'ne'): string
    {
        // यहाँ तपाईंको अनुवाद लोजिक थप्नुहोस्
        // उदाहरणका लागि: return trans("dishes.{$this->id}.name", [], $locale);
        return $this->name; // सादा नाम फर्काउँछ
    }

    /**
     * डिश ले अर्जित गरेको कुल राजस्व
     *
     * @return float
     */
    public function getTotalRevenueAttribute(): float
    {
        return $this->orders
            ->where('status', 'completed')
            ->sum(function ($order) {
                return $order->quantity * $order->price;
            });
    }

    /**
     * डिशको लोकेलाइज्ड नाम र विवरण सहितको टुथिप
     *
     * @param string $locale
     * @return string
     */
    public function getTooltipText(string $locale = 'ne'): string
    {
        $name = $this->getLocalizedName($locale);
        $description = $this->getLocalizedDescription($locale);
        $price = $this->formattedPrice;

        return "{$name} - {$description} (मूल्य: {$price})";
    }
}
