<?php

use yii\helpers\Url;
use yii\helpers\ArrayHelper;

return [
	'homeUrl'    => function ($attrs, $content, $tag) {
		return Yii::$app->homeUrl;
	},
	'currentUrl' => function ($attrs, $content, $tag) {
		$isAbsolute = ArrayHelper::getValue($attrs, 'absolute', false);

		if ($isAbsolute) {
			return Yii::$app->request->absoluteUrl;
		} else {
			return Yii::$app->request->url;
		}
	},

	'template' => function ($attrs, $content, $tag) {
		$filePath = ArrayHelper::getValue($attrs, 'file', false);

		if (!$filePath) {
			return null;
		}

		return Yii::$app->view->render($filePath);
	},

	'block' => function ($attrs, $content, $tag) {
		$code = ArrayHelper::getValue($attrs, 'code');

		if ($code === null) {
			return null;
		}

		/** @var \common\models\Block $block */
		$block = \common\models\Block::findOne(['code' => $code]);

		if ($block === null) {
			return null;
		}

		return Yii::$app->shortCodeManager->parse($block->content);
	},

	'thumbnail' => function ($attrs, $content, $tag) {
		$path = ArrayHelper::getValue($attrs, 'path');
		$preset = ArrayHelper::getValue($attrs, 'preset', 'default');

		$width = ArrayHelper::getValue($attrs, 'width');
		$height = ArrayHelper::getValue($attrs, 'height');
		$mode = ArrayHelper::getValue($attrs, 'mode', 'outbound');

		if ($path === null) {
			return null;
		}

		if ($width && $height) {
			return Yii::$app->imageManager->getThumbnailUrlByFile($path, [
				'thumbnail' => [
					'width'  => $width,
					'height' => $height,
					'mode'   => ArrayHelper::getValue([
						'outbound' => \sadovojav\image\Thumbnail::THUMBNAIL_OUTBOUND,
						'inset'    => \sadovojav\image\Thumbnail::THUMBNAIL_INSET,
					], $mode, \sadovojav\image\Thumbnail::THUMBNAIL_OUTBOUND),
				],
			]);
		}

		return Yii::$app->imageManager->getThumbnailUrlByFile($path, $preset);
	},
];