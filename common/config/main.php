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
		// read doc in http://yii2-usuario.readthedocs.io/en/latest/
		'user'        => [
			'class'                       => Da\User\Module::class,
			'generatePasswords'           => false,
			'enableEmailConfirmation'     => true,
			'enableRegistration'          => false,
			'administratorPermissionName' => 'userManagerAccess',
			'classMap'                    => [
				'User'    => 'common\models\User',
				'Profile' => 'common\models\Profile',
			],
		],
		// read doc in https://github.com/himiklab/yii2-sitemap-module/blob/master/README.md
		'sitemap'     => [
			'class'       => 'himiklab\sitemap\Sitemap',
			'enableGzip'  => true,
			'cacheExpire' => 1,
		],
		'datecontrol' => [
			'class'              => '\kartik\datecontrol\Module',

			// format settings for displaying each date attribute (ICU format example)
			'displaySettings'    => [
				\kartik\datecontrol\Module::FORMAT_DATE     => 'php:d.m.Y',
				\kartik\datecontrol\Module::FORMAT_TIME     => 'php:H:i',
				\kartik\datecontrol\Module::FORMAT_DATETIME => 'php:d.m.Y H:i',
			],

			// format settings for saving each date attribute (PHP format example)
			'saveSettings'       => [
				\kartik\datecontrol\Module::FORMAT_DATE     => 'php:Y-m-d', // saves as unix timestamp
				\kartik\datecontrol\Module::FORMAT_TIME     => 'php:H:i:s',
				\kartik\datecontrol\Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
			],

			// set your display timezone
			'displayTimezone'    => 'Europe/Moscow',

			// set your timezone for date saved to db
			'saveTimezone'       => 'UTC',

			// automatically use kartik\widgets for each of the above formats
			'autoWidget'         => true,

			// default settings for each widget from kartik\widgets used when autoWidget is true
			'autoWidgetSettings' => [
				\kartik\datecontrol\Module::FORMAT_DATE     => ['pluginOptions' => ['autoclose' => true], 'readonly' => true],
				\kartik\datecontrol\Module::FORMAT_DATETIME => ['pluginOptions' => ['autoclose' => true], 'readonly' => true],
				\kartik\datecontrol\Module::FORMAT_TIME     => ['pluginOptions' => ['autoclose' => true], 'readonly' => true],
			],
		],
	],
	'bootstrap'      => [
		'common\StartUp',
	],
	'components'     => [
		'cache'                => [
			'class'     => 'yii\caching\FileCache',
			'cachePath' => '@backend/runtime/cache',
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
						'app'             => 'app.php',
						'app.actions'     => 'app.actions.php',
						'app.roles'       => 'app.roles.php',
						'app.permissions' => 'app.permissions.php',
						'app.users'       => 'app.users.php',
						'app.messages'    => 'app.messages.php',
						'app.errors'      => 'app.errors.php',
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
		'assetManager'         => [
			'appendTimestamp' => true,
		],
		'backendUrlManager'    => require __DIR__ . '/_url-manager-backend.php',
		'frontendUrlManager'   => require __DIR__ . '/_url-manager-frontend.php',
		// view doc in https://github.com/yiisoft/yii2-authclient/blob/master/docs/guide/installation.md
		'authClientCollection' => [
			'class'   => 'yii\authclient\Collection',
			'clients' => [],
		],

		'userBuddy'        => [
			'class' => 'common\components\UserBuddy',
		],
		'imageManager'     => [
			'class'         => \common\components\ImageManager::class,
			'presets'       => require(__DIR__ . '/_thumbnail-presets.php'),
			'uploadPath'    => dirname(dirname(__DIR__)) . '/frontend/web/image-manager/images/',
			'uploadUrl'     => 'image-manager/images/',
			'thumbnailsUrl' => 'image-manager/images/thumbnails',
		],
		'thumbnail'        => [
			'class'     => \sadovojav\image\Thumbnail::class,
			'basePath'  => dirname(dirname(__DIR__)) . '/frontend/web/image-manager/images',
			'cachePath' => dirname(dirname(__DIR__)) . '/frontend/web/image-manager/images/thumbnails',
		],
		'shortCodeManager' => [
			'class'     => 'tpoxa\shortcodes\Shortcode',
			'callbacks' => require(__DIR__ . '/_short-codes.php'),
		],

		'view' => [
			'theme' => [
				'pathMap' => [
					'@Da/User/resources/views' => '@app/views/usuario',
				],
			],
		],
	],
];
