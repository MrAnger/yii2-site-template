<?php

namespace MrAnger\Yii2_HtmlEditorWidget;

use yii\web\AssetBundle;

class HtmlEditorAsset extends AssetBundle {
	public function init() {
		parent::init();

		$this->sourcePath = __DIR__ . "/assets";
	}

	public $js = [
		'html_editor.js',
	];

	public $depends = [
		'yii\web\JqueryAsset',
		'common\assets\VueJsAsset',
		'trntv\aceeditor\AceEditorAsset',
		'mranger\ckeditor\CKEditorAsset',
	];
}