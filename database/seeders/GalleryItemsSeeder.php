<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GalleryItemsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('gallery_items')->insert([
            'title' => 'Demo Video',
            'description' => 'Demo video description in Nepali',
            'type' => 'video',
            'video_url' => 'https://www.example.com/demo-video.mp4',
            'image_path' => null,
            'is_featured' => true,
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
