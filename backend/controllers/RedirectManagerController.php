<?php

namespace backend\controllers;

use common\models\RedirectEntry;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * @author MrAnger
 */
class RedirectManagerController extends BaseController {
	public function actionIndex() {
		$dataProvider = new ActiveDataProvider([
			'query' => RedirectEntry::find(),
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionCreate() {
		$model = new RedirectEntry();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->session->addFlash('success', 'Редирект успешно создан.');

			return $this->redirect(['update', 'id' => $model->id]);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	public function actionUpdate($id) {
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->session->addFlash('success', 'Редирект успешно изменен.');

			return $this->redirect(['update', 'id' => $model->id]);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	public function actionDelete($id) {
		$model = $this->findModel($id);

		$model->delete();

		Yii::$app->session->addFlash('success', 'Редирект успешно удален.');

		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * @param mixed $pk
	 *
	 * @return RedirectEntry
	 *
	 * @throws NotFoundHttpException
	 */
	protected function findModel($pk) {
		if (($model = RedirectEntry::findOne($pk)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
