<?php

$metaTag = get_meta_tags('https://hugohamonangan.vercel.app/');
$description = $metaTag['description'];
$keywords = $metaTag['keywords'];

var_dump($keywords);

// for ($i = 0; $i < count($keywords); $i++) {
//     echo "$i. " . $keywords[$i] . PHP_EOL;
// }
