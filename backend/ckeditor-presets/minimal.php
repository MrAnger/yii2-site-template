<?php

use yii\helpers\Url;

return array_merge(
	require(__DIR__ . '/_main.php'),
	[
		'toolbarGroups' => [
			['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup', 'list', 'colors']],
			['name' => 'paragraph', 'groups' => ['align']],
			/*['name' => 'document', 'groups' => ['mode']],*/
		],
		'height'        => 180,

		'autoGrow_onStartup' => true,
		'autoGrow_minHeight' => 180,
		'autoGrow_maxHeight' => 600,

		'removeButtons' => 'Subscript,Superscript,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,Form,TextField,Textarea,Radio,Button,HiddenField,Select,ImageButton',
	]
);