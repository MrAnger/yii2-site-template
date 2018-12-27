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

		['class' => 'frontend\components\PageUrlRule'],

		['pattern' => 'sitemap', 'route' => 'sitemap/default/index', 'suffix' => '.xml'],
	],
];
