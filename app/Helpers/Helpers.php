<?php

namespace App\Helpers;

if (!function_exists('getYoutubeId')) {
    function getYoutubeId(string $url): ?string {
        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }
}
