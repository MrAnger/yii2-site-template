<?php

return [
	'@common'   => dirname(__DIR__),
	'@frontend' => dirname(dirname(__DIR__)) . '/frontend',
	'@backend'  => dirname(dirname(__DIR__)) . '/backend',
	'@console'  => dirname(dirname(__DIR__)) . '/console',

	'@bower' => '@vendor/bower-asset',
	'@npm'   => dirname(dirname(__DIR__)) . '/node_modules',

	'MrAnger/Yii2_ImageManager' => dirname(dirname(__DIR__)) . '/extensions/mranger/yii2-image-manager',
	'MrAnger/Yii2_HtmlEditorWidget' => dirname(dirname(__DIR__)) . '/extensions/mranger/yii2-html-editor-widget',
];
