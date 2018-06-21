<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class FrontendAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'static/css/site.css',
	];
	public $js = [
		'static/js/jquery.format-1.3.min.js',
		'static/js/common.js',
	];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}
