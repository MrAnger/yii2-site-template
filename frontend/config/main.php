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
			'enableFlashMessages' => false,
			'controllerMap'       => [
				'security'     => 'frontend\controllers\usuario\SecurityController',
				'recovery'     => 'frontend\controllers\usuario\RecoveryController',
				'registration' => 'frontend\controllers\usuario\RegistrationController',
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
		'urlManager'   => function () {
			return Yii::$app->frontendUrlManager;
		},
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
	],
	'params'              => $params,
];

return $config;
