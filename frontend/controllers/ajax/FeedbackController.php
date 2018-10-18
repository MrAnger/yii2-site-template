<?php

namespace frontend\controllers\ajax;

use common\components\FiveCrm;
use common\helpers\MailHelper;
use common\models\FeedbackEntry;
use common\models\forms\BaseFeedbackForm;
use common\models\forms\RequestTranslateForm;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\debug\components\search\matchers\Base;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * @author MrAnger
 */
class FeedbackController extends BaseController {
	public function actionCreate($type) {
		$request = Yii::$app->request;

		$output = [
			'status' => true,
		];

		$model = BaseFeedbackForm::getFormByType($type);

		if ($model === null)
			throw new NotFoundHttpException("Feedback model[type:$type] not found.");

		$model->setScenario($model::SCENARIO_CREATE);
		$model->load($request->post());

		$model->file = UploadedFile::getInstances($model, 'file');

		$model->validate();

		if (!$model->hasErrors()) {
			$mailSubject = "Новое обращение через форму обратной связи на сайте " . Yii::$app->name;

			try {
				$formData = $model->getFormData();
				$html = $model::getFormDataAsHtml($formData);

				$feedbackEntryModel = new FeedbackEntry([
					'text' => $html,
				]);

				$output['status'] = (boolean)$feedbackEntryModel->save();

				$emailList = $model->getNotificationEmailList();

				if (!empty($emailList)) {
					MailHelper::sendMail(null, $emailList, $mailSubject, 'admin-feedback-notification', [
						'model'      => $model,
						'data'       => $formData,
						'dataAsHtml' => $html,
					]);
				}

				return $output;
			} catch (\Exception $e) {
				$output['status'] = false;

				$output['errors']['system'] = [$e->getCode() . ": " . $e->getMessage() . "\r\n" . $e->getFile() . ":" . $e->getLine() . "\r\n" . $e->getTraceAsString()];

				return $output;
			}
		}

		// Отправляем ошибки
		$output['status'] = false;
		$output['errors'] = [];
		foreach ($model->getErrors() as $attribute => $errors) {
			$output['errors'][Html::getInputId($model, $attribute)] = $errors;
		}

		return $output;
	}

	public function actionValidateForm($type) {
		$request = Yii::$app->request;

		$model = BaseFeedbackForm::getFormByType($type);

		if ($model === null)
			throw new NotFoundHttpException("Feedback model[type:$type] not found.");

		$model->setScenario($model::SCENARIO_VALIDATE);

		$model->load($request->post());

		$result = ActiveForm::validate($model);

		return $result;
	}
}