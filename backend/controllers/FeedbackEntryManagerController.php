<?php

namespace backend\controllers;

use common\models\FeedbackEntry;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * @author MrAnger
 */
class FeedbackEntryManagerController extends BaseController {
	public function actionIndex() {
		$dataProvider = new ActiveDataProvider([
			'query' => FeedbackEntry::find()
				->orderBy(['created_at' => SORT_DESC]),
			'sort'  => false,
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionDelete($id) {
		$model = $this->findModel($id);

		$result = (boolean)$model->delete();

		if ($result) {
			Yii::$app->session->addFlash('success', 'Обращение успешно удалёно.');
		}

		return $this->redirect(['index']);
	}

	public function actionMarkAsViewed($id) {
		$model = $this->findModel($id);

		$result = (boolean)$model->updateAttributes(['is_viewed' => 1]);

		if ($result) {
			Yii::$app->session->addFlash('success', 'Обращение помечено просмотренным.');
		}

		return $this->redirect(['index']);
	}

	/**
	 * @param mixed $pk
	 *
	 * @return FeedbackEntry
	 *
	 * @throws NotFoundHttpException
	 */
	protected function findModel($pk) {
		if (($model = FeedbackEntry::findOne($pk)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
