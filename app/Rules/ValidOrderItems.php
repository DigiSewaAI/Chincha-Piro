<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class ValidOrderItems implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  \Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // JSON स्ट्रिङ भएमा डिकोड गर्नुहोस्
        if (is_string($value)) {
            $value = json_decode($value, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $fail('अर्डर आइटमहरूको डेटा अमान्य छ।');
                return;
            }
        }

        // सबै आइटमहरूको लागि जाँच गर्नुहोस्
        foreach ($value as $item) {
            // बर्तन ID जाँच गर्नुहोस्
            if (!isset($item['dish_id']) || !is_numeric($item['dish_id'])) {
                $fail('प्रत्येक आइटममा वैध पकवान आईडी हुनुपर्छ।');
                return;
            }

            // उपलब्धता जाँच गर्नुहोस्
            $dish = DB::table('dishes')
                ->where('id', $item['dish_id'])
                ->where('is_available', true)
                ->first();

            if (!$dish) {
                $fail('केही पकवानहरू अमान्य वा उपलब्ध छैनन्');
                return;
            }

            // मात्रा जाँच गर्नुहोस्
            if (!isset($item['quantity']) || !is_numeric($item['quantity']) || $item['quantity'] < 1 || $item['quantity'] > 10) {
                $fail('प्रत्येक आइटमको मात्रा 1 देखि 10 को बीचमा हुनुपर्छ।');
                return;
            }
        }
    }
}
