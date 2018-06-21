<?php

namespace common\assets;

use yii\web\AssetBundle;
use yii\web\View;

class GoogleChartsAsset extends AssetBundle {
	public $js = [
		'//www.gstatic.com/charts/loader.js',
	];

	public $jsOptions = [
		'position' => View::POS_HEAD,
	];
}
