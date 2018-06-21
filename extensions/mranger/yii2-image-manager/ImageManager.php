<?php

namespace MrAnger\Yii2_ImageManager;

use MrAnger\Yii2_ImageManager\models\Image;
use sadovojav\image\Thumbnail;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * @author MrAnger
 */
class ImageManager extends Component {
	/**
	 * @var string
	 * Папка, в которую
	 */
	public $uploadPath = "@webroot/uploads/images/";

	/**
	 * @var string
	 * Путь(URL) по которому будет доступно оригинальное изображение
	 */
	public $uploadUrl = "uploads/images/";

	/**
	 * @var string
	 * Путь(URL) по которому будет доступно превью изображения
	 */
	public $thumbnailsUrl = "uploads/images/thumbnails";

	/** @var array */
	public $presets = [];

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();

		$this->uploadPath = Yii::getAlias($this->uploadPath);
	}

	/**
	 * @param UploadedFile $file
	 * @param boolean $deleteTempFile
	 *
	 * @return Image
	 *
	 * @throws \Exception
	 */
	public function upload(UploadedFile $file, $deleteTempFile = true) {
		$model = $this->createImage($file->name);

		$filePath = $this->uploadPath . $model->file;

		$fileInfo = pathinfo($filePath);

		if (!is_dir($fileInfo['dirname'])) {
			FileHelper::createDirectory($fileInfo['dirname']);
		}

		$result = $file->saveAs($filePath, $deleteTempFile);

		if (!$result) {
			throw new \Exception("Failed to upload image '$filePath'. Perhaps the file size limit exceeded. Also you can check whether the target directory has necessary permissions.");
		}

		if (!$model->save()) {
			Yii::error("Failed to save image model instance.");
		}

		return $model;
	}

	/**
	 * @param Image|integer $image
	 *
	 * @return boolean
	 *
	 * @throws
	 */
	public function deleteImage($image) {
		if (is_integer($image) || is_string($image)) {
			$image = Image::findOne($image);
		}

		if ($image->delete()) {
			return @unlink($this->uploadPath . $image->file);
		}

		return false;
	}

	/**
	 * @param Image $image
	 *
	 * @return string
	 */
	public function getImagePath($image) {
		if (!$image instanceof Image) {
			$image = Image::findOne($image);
		}

		if ($image !== null) {
			return $image->file;
		}

		return null;
	}

	/**
	 * @param Image $image
	 *
	 * @return string
	 */
	public function getImageFullPath($image) {
		if (!$image instanceof Image) {
			$image = Image::findOne($image);
		}

		if ($image !== null) {
			return $this->uploadPath . $image->file;
		}

		return null;
	}

	/**
	 * @param Image|integer $image
	 *
	 * @return string
	 */
	public function getOriginalUrl($image) {
		if (!$image instanceof Image) {
			$image = Image::findOne($image);
		}

		if ($image !== null) {
			return Url::to($this->uploadUrl . $image->file);
		}

		return null;
	}

	/**
	 * @param Image $image
	 * @param string $presetId
	 *
	 * @return string
	 */
	public function getThumbnailUrl($image, $presetId = 'default') {
		/** @var Thumbnail $thumbnail */
		$thumbnail = Yii::$app->get('thumbnail');

		if ($image !== null) {
			return $thumbnail->url($this->getImagePath($image), $this->getPresetDefinition($presetId));
		}

		return null;
	}

	/**
	 * @param integer[] $imageIdList
	 *
	 * @return array
	 */
	public function getOriginalBucket($imageIdList) {
		$output = [];

		/** @var Image[] $models */
		$models = Image::find()
			->where(['IN', 'id', $imageIdList])
			->all();

		foreach ($models as $model) {
			$output[$model->id] = $this->getOriginalUrl($model);
		}

		return $output;
	}

	/**
	 * @param integer[] $imageIdList
	 * @param string[] $presetList
	 *
	 * @return array
	 */
	public function getThumbnailBucket($imageIdList, $presetList) {
		$output = [];

		/** @var Image $imageList */
		$imageList = Image::find()
			->andWhere(['in', 'id', $imageIdList])
			->all();

		foreach ($imageList as $image) {
			$tmp = [];

			foreach ($presetList as $presetId) {
				$tmp[$presetId] = $this->getThumbnailUrl($image, $presetId);
			}

			$output[$image->id] = $tmp;
		}

		return $output;
	}

	/**
	 * @param $fileName
	 *
	 * @return Image
	 */
	protected function createImage($fileName) {
		$fileInfo = pathinfo($fileName);

		$targetName = time() . '-' . md5(uniqid() . $fileInfo['basename']) . '.' . $fileInfo['extension'];

		$model = new Image([
			'file' => $targetName,
		]);

		return $model;
	}

	/**
	 * @param string $id
	 *
	 * @return array
	 */
	protected function getPresetDefinition($id) {
		return ArrayHelper::getValue($this->presets, $id, []);
	}
}