<?php

return [
	'name'           => 'Project name',
	'language'       => 'ru-RU',
	'sourceLanguage' => 'en-US',
	'timeZone'       => 'Europe/Moscow',
	'aliases'        => array_merge(
		require(__DIR__ . '/aliases.php'),
		require(__DIR__ . '/aliases-local.php')
	),
	'vendorPath'     => dirname(dirname(__DIR__)) . '/vendor',
	'modules'        => [
		// read doc in https://github.com/dektrium/yii2-user/blob/master/docs/README.md
		'user'    => [
			'class'                    => 'dektrium\user\Module',
			'enableGeneratingPassword' => false,
			'enableConfirmation'       => true,
			'enableRegistration'       => false,
			'adminPermission'          => 'adminAccess',
			'modelMap'                 => [
				'User'    => 'common\models\User',
				'Profile' => 'common\models\Profile',
			],
		],
		// read doc in https://github.com/himiklab/yii2-sitemap-module/blob/master/README.md
		'sitemap' => [
			'class'       => 'himiklab\sitemap\Sitemap',
			'enableGzip'  => true,
			'cacheExpire' => 1,
		],
	],
	'bootstrap'      => [
		'common\StartUp',
	],
	'components'     => [
		'cache'                => [
			'class' => 'yii\caching\FileCache',
		],
		'log'                  => [
			'traceLevel'    => YII_DEBUG ? 3 : 0,
			'flushInterval' => 30,
			'targets'       => [
				[
					'class'  => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		'i18n'                 => [
			'translations' => [
				'app*' => [
					'class'          => 'yii\i18n\PhpMessageSource',
					'basePath'       => '@common/messages',
					'sourceLanguage' => 'en-US',
					'fileMap'        => [
						'app'          => 'app.php',
						'app.actions'  => 'app.actions.php',
						'app.roles'    => 'app.roles.php',
						'app.users'    => 'app.users.php',
						'app.messages' => 'app.messages.php',
						'app.errors'   => 'app.errors.php',
					],
				],
			],
		],
		'formatter'            => [
			'thousandSeparator' => ' ',
			'decimalSeparator'  => ',',
			'defaultTimeZone'   => 'Europe/Moscow',
			'dateFormat'        => 'php: j F Y',
			'datetimeFormat'    => 'php: j F Y G:i:s',
		],
		'authManager'          => [
			'class' => 'yii\rbac\DbManager',
		],
		'urlManager'           => [
			'enablePrettyUrl' => true,
			'showScriptName'  => false,
		],
		// view doc in https://github.com/yiisoft/yii2-authclient/blob/master/docs/guide/installation.md
		'authClientCollection' => [
			'class'   => 'yii\authclient\Collection',
			'clients' => [],
		],

		'userBuddy' => [
			'class' => 'common\components\UserBuddy',
		],
	],
];
