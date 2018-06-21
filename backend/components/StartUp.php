<?php

namespace backend\components;

use common\Rbac;
use Yii;
use yii\base\ActionEvent;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\web\Application;

/**
 * @author MrAnger
 */
class StartUp implements BootstrapInterface {

	/**
	 * @inheritDoc
	 */
	public function bootstrap($app) {
		// Так как у нас данные индетификации одинаковые как для фронтенда, так и для бэкенда,
		// то необходима дополнительная проверка, может ли авторизованный пользователь смотреть в сторону админки вообще :)
		Event::on(Application::class, Application::EVENT_AFTER_ACTION, function (ActionEvent $event) {
			if (!Yii::$app->user->isGuest && !Yii::$app->user->can(Rbac::PERMISSION_CONTROL_PANEL_ACCESS)) {
				Yii::$app->controller->redirect(Yii::$app->frontendUrlManager->createAbsoluteUrl(['/site/index'], true));
			}
		});
	}
}