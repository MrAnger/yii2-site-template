<?php

$params = array_merge(
	require(__DIR__ . '/../../common/config/params.php'),
	require(__DIR__ . '/../../common/config/params-local.php'),
	require(__DIR__ . '/params.php'),
	require(__DIR__ . '/params-local.php')
);

return [
	'id'                  => 'app-backend',
	'basePath'            => dirname(__DIR__),
	'controllerNamespace' => 'backend\controllers',
	'bootstrap'           => [
		'log',
		'backend\components\StartUp',
	],
	'modules'             => [
		'user' => [
			'enableFlashMessages' => true,
			'controllerMap'       => [
				'security'     => 'backend\controllers\SecurityController',
				'recovery'     => 'backend\controllers\RecoveryController',
				'registration' => 'backend\controllers\RegistrationController',
			],
			'modelMap'            => [
				'LoginForm' => 'backend\models\LoginForm',
			],
		],
	],
	'components'          => [
		'request'      => [
			'baseUrl' => '/cp',
		],
		'urlManager'   => [
			'scriptUrl' => '/cp/index.php',
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
		'view'         => [
			'theme' => [
				'pathMap' => [
					'@dektrium/user/views' => '@app/views/user',
				],
			],
		],
	],
	'params'              => $params,
];
