<?php

namespace common\assets;

use yii\web\AssetBundle;

/**
 * @author MrAnger
 */
class SortableJsAsset extends AssetBundle {
	public $sourcePath = "@bower/sortablejs";

	public $js = [
		'Sortable.min.js'
	];
}