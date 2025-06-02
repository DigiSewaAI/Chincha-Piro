<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | यहाँ तपाईंले फ्रेमवर्क द्वारा प्रयोग गर्नुपर्ने डिफल्ट फाइलसिस्टम डिस्क निर्दिष्ट गर्न सक्नुहुन्छ।
    | "local" डिस्कका साथै क्लाउड आधारित डिस्कहरू पनि तपाईंको एप्लिकेशनका लागि उपलब्ध छन्।
    | सामान्यतया "local" डिफल्टका लागि प्रयोग गरिन्छ।
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | यहाँ तपाईंले जति सकेसम्म फाइलसिस्टम "डिस्कहरू" कन्फिगर गर्न सक्नुहुन्छ,
    | र तपाईंले समान ड्राइभरको धेरै डिस्कहरू पनि कन्फिगर गर्न सक्नुहुन्छ।
    | समर्थित ड्राइभरहरू: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        // ✅ public डिस्कको सही सेटिङहरू
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'), // ✅ सही root path
            'url' => env('APP_URL') . '/storage', // ✅ APP_URL बाट URL जनरेट गर्ने
            'visibility' => 'public', // ✅ public फाइलहरूको visibility
            'throw' => false, // ✅ error throw नगर्ने
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | यहाँ तपाईंले सिम्बोलिक लिङ्कहरू कन्फिगर गर्न सक्नुहुन्छ जुन `storage:link` Artisan कमान्ड
    | प्रयोग गर्दा सिर्जना हुन्छन्। एर्रे कीहरू लिङ्कहरूको स्थान हुन् र मानहरू उनीहरूको लक्ष्य हुन्।
    |
    */

    'links' => [
        // ✅ public/storage → storage/app/public
        public_path('storage') => storage_path('app/public'),
    ],
];
