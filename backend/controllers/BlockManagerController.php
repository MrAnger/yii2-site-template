<?php

namespace backend\controllers;

use common\models\Block;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * @author MrAnger
 */
class BlockManagerController extends BaseController {
	public function actionIndex() {
		$dataProvider = new ActiveDataProvider([
			'query' => Block::find(),
		]);

		$dataProvider->sort->defaultOrder = ['name' => SORT_ASC];

		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionCreate() {
		$request = Yii::$app->request;

		$block = new Block();

		if ($block->load($request->post()) && $block->save()) {
			Yii::$app->session->addFlash('success', 'Блок успешно создан.');

			return $this->redirect(['update', 'id' => $block->id]);
		}

		return $this->render('create', [
			'block' => $block,
		]);
	}

	public function actionUpdate($id) {
		$request = Yii::$app->request;

		$block = $this->findModel($id);

		if ($block->load($request->post()) && $block->save()) {
			Yii::$app->session->addFlash('success', 'Блок успешно изменен.');

			return $this->redirect(['update', 'id' => $block->id]);
		}

		return $this->render('update', [
			'block' => $block,
		]);
	}

	public function actionDelete($id) {
		$model = $this->findModel($id);

		$model->delete();

		Yii::$app->session->addFlash('success', 'Блок успешно удален.');

		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * @param mixed $pk
	 *
	 * @return Block
	 *
	 * @throws NotFoundHttpException
	 */
	protected function findModel($pk) {
		if (($model = Block::findOne($pk)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
