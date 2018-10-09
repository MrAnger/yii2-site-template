<?php

namespace common\components;

use MrAnger\Yii2_ImageManager\models\Image;
use sadovojav\image\Thumbnail;
use Yii;

/**
 * @author MrAnger
 */
class ImageManager extends \MrAnger\Yii2_ImageManager\ImageManager {
	/**
	 * @param Image|integer $image
	 *
	 * @return string
	 */
	public function getOriginalUrl($image) {
		$url = parent::getOriginalUrl($image);

		return Yii::$app->frontendUrlManager->createAbsoluteUrl($url, true);
	}

	/**
	 * @param Image $image
	 * @param string $presetId
	 *
	 * @return string
	 */
	public function getThumbnailUrl($image, $presetId = 'default') {
		$url = parent::getThumbnailUrl($image, $presetId);

		return Yii::$app->frontendUrlManager->createAbsoluteUrl($this->fixThumbnailUrl($url), true);
	}

	/**
	 * @param string $file
	 * @param string|array $presetId
	 *
	 * @return string
	 *
	 * @throws
	 */
	public function getThumbnailUrlByFile($file, $presetId) {
		$file = Yii::getAlias($file);

		if (!file_exists($file)) {
			return null;
		}

		/** @var Thumbnail $thumbnailSystem */
		$thumbnailSystem = Yii::$app->get('thumbnail');

		/** @var Thumbnail $thumbnailTemp */
		$thumbnailTemp = Yii::createObject([
			'class'     => Thumbnail::className(),
			'basePath'  => dirname($file),
			'cachePath' => $thumbnailSystem->cachePath,
		]);

		$url = $thumbnailTemp->url(basename($file), $this->getPresetDefinition($presetId));

		return Yii::$app->frontendUrlManager->createAbsoluteUrl($this->fixThumbnailUrl($url), true);
	}

	/**
	 * @param integer[] $imageIdList
	 *
	 * @return array
	 */
	public function getOriginalBucket($imageIdList) {
		$output = parent::getOriginalBucket($imageIdList);

		foreach ($output as &$url) {
			$url = Yii::$app->frontendUrlManager->createAbsoluteUrl($url, true);

			unset($url);
		}

		return $output;
	}

	public function getThumbnailBucket($imageIdList, $presetList) {
		$output = parent::getThumbnailBucket($imageIdList, $presetList);

		foreach ($output as $imageId => &$data) {
			foreach ($data as $presetId => &$url) {
				$url = $this->fixThumbnailUrl($url);

				unset($url);
			}

			unset($data);
		}

		return $output;
	}

	/**
	 * @param string $url
	 *
	 * @return string
	 */
	private function fixThumbnailUrl($url) {
		/** @var Thumbnail $thumbnail */
		$thumbnail = Yii::$app->get('thumbnail');

		$url = str_replace(str_replace("\\", "/", $thumbnail->cachePath), $this->thumbnailsUrl, $url);

		return $url;
	}
}