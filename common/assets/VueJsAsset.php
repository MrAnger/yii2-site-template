<?php

namespace common\assets;

use yii\web\AssetBundle;

/**
 * @author MrAnger
 */
class VueJsAsset extends AssetBundle {
	public $sourcePath = "@bower/vue/dist";

	public $js = [
		'vue.js',
	];
}