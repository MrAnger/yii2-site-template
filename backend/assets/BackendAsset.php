<?php

namespace backend\assets;

use yii\web\AssetBundle;

class BackendAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'static/css/site.css',
	];
	public $js = [
		'static/js/main.js',
	];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}
