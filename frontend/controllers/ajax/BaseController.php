<?php

namespace frontend\controllers\ajax;

use yii\web\Response;

abstract class BaseController extends \frontend\controllers\BaseController {
	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return array_merge(parent::behaviors(), [
			'contentNegotiator' => [
				'class'   => \yii\filters\ContentNegotiator::class,
				'formats' => [
					'application/json' => Response::FORMAT_JSON,
				],
			],
		]);
	}
}