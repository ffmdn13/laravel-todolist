<?php

use App\Models\Lists;
use App\Models\Notebook;
use App\Models\Tag;

/**
 * Get last edited time
 */
function getLastEdited($timestamp = null)
{
    $time = time() - $timestamp;
    $times = [
        [60, ' second ago'],
        [60, ' minutes ago'],
        [24, ' hours ago'],
        [7, ' days ago'],
        [30, ' months ago'],
        [12, ' years ago']
    ];

    for ($i = 0; $i < 6; $i++) {
        if ($time < $times[$i][0]) {
            return $time . $times[$i][1];
        }

        $time = floor($time / $times[$i][0]);
    }
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
    return Tag::select(['id', 'title'])
        ->where('user_id', $userId)
        ->get();
}

/**
 * Return Notebook model for blade template that rendered by @include directive
 */
function getNotebooks($userId)
{
    return null;
}
