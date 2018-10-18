<?php

namespace backend\controllers;

use common\models\Block;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * @author MrAnger
 */
class SettingManagerController extends BaseController {
	public function actionIndex() {
		return $this->render('index');
	}

	public function actionCacheReset() {
		if (Yii::$app->cache->flush()) {
			Yii::$app->session->addFlash('success', 'Кеш успешно очищен.');
		} else {
			Yii::$app->session->addFlash('warning', 'Не удалось очистить кеш.');
		}

		return $this->redirect(Yii::$app->request->referrer);
	}
}
