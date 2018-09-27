<?php

namespace common\assets;

use yii\web\AssetBundle;

class DropzoneAsset extends AssetBundle {
	public $sourcePath = '@bower';

	public $css = [
		'dropzone/dist/min/dropzone.min.css',
	];

	public $js = [
		'dropzone/dist/min/dropzone.min.js',
	];

	public $depends = [
		'yii\web\JqueryAsset',
	];
}