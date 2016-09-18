<?php

use yii\helpers\Url;

return [
	'customConfig'    => Url::to(Yii::$app->urlManager->baseUrl . '/static/js/ckeditor.config.js', true),
	'externalPlugins' => [
		[
			'name'     => 'codemirror',
			'url'      => '/static/js/ckeditor-plugins/codemirror/',
			'fileName' => 'plugin.js',
		],
	],
];