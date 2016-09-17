<?php

namespace frontend\controllers;

use Yii;

/**
 * @author MrAnger
 */
class SiteController extends BaseController {
	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}

	/**
	 * @return string
	 */
	public function actionIndex() {
		return $this->render('index');
	}
}