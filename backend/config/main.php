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
			'enableFlashMessages' => false,
			'controllerMap'       => [
				'security'     => 'backend\controllers\usuario\SecurityController',
				'recovery'     => 'backend\controllers\usuario\RecoveryController',
				'registration' => 'backend\controllers\usuario\RegistrationController',

				'admin'      => 'backend\controllers\usuario\AdminController',
				'role'       => 'backend\controllers\usuario\RoleController',
				'permission' => 'backend\controllers\usuario\PermissionController',
				'rule'       => 'backend\controllers\usuario\RuleController',
			],
			'classMap'            => [
				'LoginForm' => 'backend\models\LoginForm',
			],
		],
	],
	'components'          => [
		'request'      => [
			'baseUrl' => '/cp',
		],
		'urlManager'   => function () {
			return Yii::$app->backendUrlManager;
		},
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
	],
	'params'              => $params,
];
