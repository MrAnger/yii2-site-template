<?php

use yii\helpers\Url;

return array_merge(
	require(__DIR__ . '/_main.php'),
	[
		'height'               => 400,
		'extraPlugins'         => 'codemirror',
		'toolbarGroups'        => [
			['name' => 'basicstyles', 'groups' => ['basicstyles', 'colors', 'cleanup']],
			['name' => 'styles'],
			['name' => 'blocks'],
			['name' => 'colors'],
			['name' => 'document', 'groups' => ['mode', 'document', 'doctools']],
			['name' => 'tools'],
			['name' => 'others'],
			'/',
			['name' => 'paragraph', 'groups' => ['list', 'indent', 'blocks', 'align', 'bidi']],
			['name' => 'links'],
			['name' => 'insert'],
			['name' => 'clipboard', 'groups' => ['clipboard', 'undo']],
			['name' => 'editing', 'groups' => ['find', 'selection', 'spellchecker']],
		],
		'filebrowserUploadUrl' => Url::to(['/wysiwyg/ckeditor-file-upload'], true),
	]
);