<?php

namespace common;

use common\models\Profile;
use common\models\User;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\AfterSaveEvent;

/**
 * @author MrAnger
 */
class StartUp implements BootstrapInterface {

	/**
	 * @inheritDoc
	 */
	public function bootstrap($app) {
		// После создания нового профиля пользователя, проверяем настроен ли емайл gravatar, если нет, то устанавливаем его принудительно
		Event::on(Profile::className(), Profile::EVENT_AFTER_INSERT, function (AfterSaveEvent $event) {
			/** @var Profile $profile */
			$profile = $event->sender;

			if ($profile->gravatar_email === null) {
				$profile->gravatar_email = $profile->user->email;

				$profile->save(false);
			}
		});

		// Прописываем правильный IP адрес при создании пользователя
		Event::on(User::className(), User::EVENT_AFTER_INSERT, function (Event $event) {
			/** @var User $user */
			$user = $event->sender;

			if (isset($_SERVER['HTTP_X_REAL_IP'])) {
				$user->updateAttributes(['registration_ip' => $_SERVER['HTTP_X_REAL_IP']]);
			}
		});
	}
}