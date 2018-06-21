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
		'index.html' => '/',

		['pattern' => 'sitemap', 'route' => 'sitemap/default/index', 'suffix' => '.xml'],
	],
];
