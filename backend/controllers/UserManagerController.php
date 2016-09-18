<?php

namespace backend\controllers;

use backend\models\UserSearch;
use Yii;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * @author MrAnger
 */
class UserManagerController extends BaseController {
	/**
	 * @return mixed
	 */
	public function actionIndex() {
		$searchModel = new UserSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'searchModel'  => $searchModel,
		]);
	}

	/**
	 * @param string $id
	 *
	 * @return mixed
	 *
	 * @throws \Exception
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);

		$model->loadRoles();

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$transaction = Yii::$app->db->beginTransaction();

			try {
				$model->assignRoles();

				$model->save(false);

				$transaction->commit();

				return $this->redirect(['update', 'id' => $model->id]);
			} catch (\Exception $e) {
				$transaction->rollBack();

				throw $e;
			}
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * @param string $id
	 *
	 * @return User
	 *
	 * @throws NotFoundHttpException
	 */
	protected function findModel($id) {
		if (($model = User::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
