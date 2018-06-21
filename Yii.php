<?php

/**
 * Yii bootstrap file. Used for enhanced IDE code autocompletion.
 */
class Yii extends \yii\BaseYii {
	/**
	 * @var BaseApplication|WebApplication|ConsoleApplication the application instance.
	 */
	public static $app;
}

/**
 * @property \yii\web\UrlManager $frontendUrlManager
 * @property \yii\web\UrlManager $backendUrlManager
 * @property \common\components\ImageManager $imageManager
 */
abstract class BaseApplication extends yii\base\Application {
};

/**
 *
 */
class WebApplication extends yii\web\Application {
}

/**
 *
 */
class ConsoleApplication extends yii\console\Application {
}