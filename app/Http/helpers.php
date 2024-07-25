<?php

use App\Models\Lists;
use App\Models\Notebook;
use App\Models\Tag;

/**
 * Get last edited time
 */
function getLastEdited(int $timestamp = null)
{
    if (is_null($timestamp)) {
        return $timestamp;
    }

    $time = time() - $timestamp;
    $times = [
        [60, 60, ' seconds ago'],
        [60, 60, ' minutes ago'],
        [24, 3600, ' hours ago'],
        [7, 86400, ' days ago'],
        [30, 604800, ' weeks ago'],
        [12, 31104000, ' months ago']
    ];

    if ($time < $times[0][0]) {
        return $time . $times[0][2];
    }

    for ($i = 1; $i < 4; $i++) {
        $lastEdited = floor($time / $times[$i][1]);
        if ($lastEdited < $times[$i][0]) {
            return $lastEdited . $times[$i][2];
        }
    }

    return floor($time / $times[5][1]) . ' years ago';
}

function formatDateOrTime(?string $format = null, ?int $timestamp = null, string $default = null)
{
    if (is_null($format) || is_null($timestamp)) {
        return $default;
    }

    return date($format, $timestamp);
}

/**
 * Set apropriaate delimiter for order by query parameters
 */
function setDelimiterForOrderByUrl()
{
    $url = request()->fullUrlWithoutQuery(['order', 'direction']);
    return $url .= preg_match('/\?/', $url) === 1 ? '&' : '?';
}

/**
 * Return Lists model for blade template that rendered by @include directive
 */
function getLists($userId)
{
    return Lists::select(['id', 'title'])
        ->where('user_id', $userId)
        ->get();
}
/**
 * Return Tag model for blade template that rendered by @include directive
 */
function getTags($userId)
{
    return Tag::select(['id', 'title', 'color'])
        ->where('user_id', $userId)
        ->get();
}

/**
 * Return Notebook model for blade template that rendered by @include directive
 */
function getNotebooks($userId)
{
    return Notebook::select(['id', 'title'])
        ->where('user_id', $userId)
        ->get();
}
