<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * @author MrAnger
 */
class ThemeAsset extends AssetBundle {
	public $basePath = '@webroot/theme';
	public $baseUrl = '@web/theme';

	public $css = [
		'css/style.default.css',
	];

	public $js = [
		'js/jquery.cookies.js',
		'js/modernizr.min.js',
		'js/pace.min.js',
		'js/retina.min.js',
		'js/custom.js',
	];

	public $depends = [
		'yii\web\JqueryAsset',
		'yii\bootstrap\BootstrapAsset',
		'yii\bootstrap\BootstrapPluginAsset',
	];
}
