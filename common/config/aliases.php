<?php

return [
	'@common'   => dirname(__DIR__),
	'@frontend' => dirname(dirname(__DIR__)) . '/frontend',
	'@backend'  => dirname(dirname(__DIR__)) . '/backend',
	'@console'  => dirname(dirname(__DIR__)) . '/console',

	'@bower' => '@vendor/bower-asset',
	'@npm'   => '@vendor/npm-asset',

	'MrAnger/Yii2_ImageManager' => dirname(dirname(__DIR__)) . '/extensions/mranger/yii2-image-manager',
];
