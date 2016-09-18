<?php

use himiklab\sitemap\behaviors\SitemapBehavior;
use yii\helpers\Url;

$urls = [
	'/',
];

$output = [];
$defaultItem = [
	'loc'        => '',
	'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
	'priority'   => 0.8,
];

foreach ($urls as $url) {
	$output[] = array_merge($defaultItem, ['loc' => Url::to($url)]);
}

return $output;
