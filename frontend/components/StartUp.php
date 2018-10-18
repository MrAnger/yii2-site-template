<?php

namespace frontend\components;

use common\models\RedirectEntry;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\web\Response;

/**
 * @author MrAnger
 */
class StartUp implements BootstrapInterface {
	/**
	 * @inheritDoc
	 */
	public function bootstrap($app) {
		// Проверяем, нужно ли сделать редирект
		Event::on(Response::className(), Response::EVENT_BEFORE_SEND, function (Event $event) {
			/** @var RedirectEntry $redirectModel */
			$redirectModel = RedirectEntry::findOne(['from' => Yii::$app->request->url]);

			if ($redirectModel) {
				Yii::$app->response->redirect($redirectModel->to, $redirectModel->code);
			}
		});
	}
}