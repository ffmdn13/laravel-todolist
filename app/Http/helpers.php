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

function getDueDate($timestampForDate, $timestampForTime, $timeFormat = '24hr')
{
    /**
     * Time format to use :
     * 1. 24hr : l, M j Y H:i
     * 2. 12hr : l, M j Y h:i A
     */

    if (is_null($timestampForTime)) {
        $currentDate =  [
            'date' => date('l, M j Y', $timestampForDate),
            'dateValue' => date('Y-m-d', $timestampForDate),
        ];
    } else {
        $timeFormats = ['24hr' => ' H:i', '12hr' => ' h:i A'];
        $timeFormat = $timeFormats[$timeFormat];
        $time = date($timeFormat, $timestampForTime);

        $date = date('l, M j Y', $timestampForDate);
        $currentDate = [
            'date' => $date . $time,
            'dateValue' => date('Y-m-d', $timestampForDate),
            'timeValue' => date('h:i', $timestampForTime)
        ];
    }

    return $currentDate;
}
