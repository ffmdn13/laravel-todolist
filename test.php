<?php

$pastTime = strtotime('2000-05-12');
$time = time() - $pastTime;
$cachedTime = $time;

$times = [
    [60, 60, ' seconds ago'],
    [60, 60, ' minutes ago'],
    [24, 3600, ' hours ago'],
    [7, 86400, ' days ago'],
    [30, 604800, ' weeks ago'],
    [12, 31104000, ' months ago']
];

for ($i = 0; $i < count($times) - 1; $i++) {
    if ($time < $times[$i][0]) {
        die($time . $times[$i][2]);
    }

    if (floor($time / $times[$i][1]) < $times[$i][0]) {
        die(floor($time / $times[$i][1]) . $times[$i][2]);
    }
}

die(floor($time / $times[5][1]));
