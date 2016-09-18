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
	'modules'             => [
		'user'    => [
			'enableFlashMessages' => true,
			'controllerMap'       => [
				'security'     => 'frontend\controllers\SecurityController',
				'recovery'     => 'frontend\controllers\RecoveryController',
				'registration' => 'frontend\controllers\RegistrationController',
			],
		],
		'sitemap' => [
			'models' => [],
			'urls'   => require(__DIR__ . '/sitemap-urls.php'),
		],
	],
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
