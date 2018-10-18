<?php

return [
	'class' => yii\web\UrlManager::class,

	'scriptUrl' => '/index.php',

	// Конфигурируйте этот параметр в main-local.php
	// 'baseUrl' => '',

	'enablePrettyUrl' => true,
	'showScriptName'  => false,
	'rules'           => [
		''           => '/site/index',

		['pattern' => '<slug>', 'route' => '/site/view-page-by-slug', 'suffix' => '/'],

		['pattern' => 'sitemap', 'route' => 'sitemap/default/index', 'suffix' => '.xml'],
	],
];
