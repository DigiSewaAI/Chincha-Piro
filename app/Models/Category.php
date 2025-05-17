<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dish; // Dish मोडलको Import

class Category extends Model
{
    use HasFactory;

    /**
     * Category ले धेरै Dishes राख्छ
     */
    public function dishes()
    {
        return $this->hasMany(Dish::class); // hasMany सम्बन्ध
    }
}
