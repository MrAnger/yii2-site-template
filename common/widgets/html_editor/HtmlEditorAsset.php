<?php

namespace common\widgets\html_editor;

use yii\web\AssetBundle;

class HtmlEditorAsset extends AssetBundle {
	public $sourcePath = "@common/widgets/html_editor/assets";

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