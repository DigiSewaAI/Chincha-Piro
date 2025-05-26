<?php

namespace App\Helpers;

if (!function_exists('getYoutubeId')) {
    function getYoutubeId($url)
    {
        $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([^\s&]+)/';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }
}
