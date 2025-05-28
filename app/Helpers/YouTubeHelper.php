<?php

if (!function_exists('getYoutubeId')) {
    function getYoutubeId(string $url): ?string
    {
        $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([^\&\?\/]+)/i';
        preg_match($pattern, $url, $matches);
        return $matches[1] ?? null;
    }
}
