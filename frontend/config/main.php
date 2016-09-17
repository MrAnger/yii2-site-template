<?php

$params = array_merge(
	require(__DIR__ . '/../../common/config/params.php'),
	require(__DIR__ . '/../../common/config/params-local.php'),
	require(__DIR__ . '/params.php'),
	require(__DIR__ . '/params-local.php')
);

$config = [
	'id'                  => 'app-frontend',
	'basePath'            => dirname(__DIR__),
	'controllerNamespace' => 'frontend\controllers',
	'modules'             => [],
	'bootstrap'           => [
		'log',
		'frontend\components\StartUp',
	],
	'components'          => [
		'request'      => [
			'baseUrl' => '',
		],
		'urlManager'   => [
			'scriptUrl' => '/index.php',
			'rules'     => require(__DIR__ . '/routes.php'),
		],
		'cache'        => [
			'class' => 'yii\caching\FileCache',
		],
		'assetManager' => [
			'appendTimestamp' => true,
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
	],
	'params'              => $params,
];

return $config;
