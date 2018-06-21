<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * @author Cyrill Tekord
 */
class BowerAsset extends AssetBundle {
	public $sourcePath = "@bower";

	public $css = [];

	public $js = [];

	public $depends = [
		'yii\web\JqueryAsset',
	];
}
