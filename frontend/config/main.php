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
			'models' => [
				'common\models\Page',
			],
			'urls'   => require(__DIR__ . '/sitemap-urls.php'),
		],
	],
	'bootstrap'           => [
		'log',
		'frontend\components\StartUp',
		'assetsAutoCompress',
	],
	'components'          => [
		'request'            => [
			'baseUrl' => '',
		],
		'urlManager'         => function () {
			return Yii::$app->frontendUrlManager;
		},
		'errorHandler'       => [
			'errorAction' => 'site/error',
		],
		'assetsAutoCompress' => [// see https://github.com/skeeks-semenov/yii2-assets-auto-compress
			'class' => '\skeeks\yii2\assetsAuto\AssetsAutoCompressComponent',

			'enabled' => true,

			'readFileTimeout' => 3,           //Time in seconds for reading each asset file

			'jsCompress'                => true,        //Enable minification js in html code
			'jsCompressFlaggedComments' => true,        //Cut comments during processing js

			'cssCompress' => true,        //Enable minification css in html code

			'cssFileCompile'        => true,        //Turning association css files
			'cssFileRemouteCompile' => false,       //Trying to get css files to which the specified path as the remote file, skchat him to her.
			'cssFileCompress'       => true,        //Enable compression and processing before being stored in the css file
			'cssFileBottom'         => false,       //Moving down the page css files
			'cssFileBottomLoadOnJs' => false,       //Transfer css file down the page and uploading them using js

			'jsFileCompile'                 => true,        //Turning association js files
			'jsFileRemouteCompile'          => false,       //Trying to get a js files to which the specified path as the remote file, skchat him to her.
			'jsFileCompress'                => true,        //Enable compression and processing js before saving a file
			'jsFileCompressFlaggedComments' => true,        //Cut comments during processing js

			'noIncludeJsFilesOnPjax' => true,        //Do not connect the js files when all pjax requests

			'htmlFormatter' => [
				//Enable compression html
				'class'         => 'skeeks\yii2\assetsAuto\formatters\html\TylerHtmlCompressor',
				'extra'         => false,       //use more compact algorithm
				'noComments'    => true,        //cut all the html comments
				'maxNumberRows' => 50000,       //The maximum number of rows that the formatter runs on
			],
		],
	],
	'params'              => $params,
];

return $config;
