<?php

namespace common\models\forms;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ImageUploadForm extends Model {
	/**
	 * @var UploadedFile
	 */
	public $file;

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['file'], 'file', 'extensions' => ['jpg', 'jpeg', 'png', 'bmp', 'gif']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'file' => 'Файл изображения',
		];
	}
}
