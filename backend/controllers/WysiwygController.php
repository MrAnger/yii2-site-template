<?php

namespace backend\controllers;

use mranger\ckeditor\actions\FileUploadAction;
use Yii;

/**
 * @author MrAnger
 */
class WysiwygController extends BaseController {
	public $enableCsrfValidation = false;

	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'ckeditor-file-upload' => [
				'class' => FileUploadAction::class,
			],
		];
	}
}
