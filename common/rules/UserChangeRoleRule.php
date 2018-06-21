<?php

namespace common\rules;

use common\Rbac;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii\rbac\Rule;

class UserChangeRoleRule extends Rule {
	public $name = 'isUserChangeRoleAllowed';

	/**
	 * @param integer $currentUserId
	 * @param Item $item
	 * @param array $params
	 *
	 * @return bool
	 */
	public function execute($currentUserId, $item, $params) {
		$authManager = Yii::$app->authManager;

		// Если ID пользователя, которого хотят изменить не передали, то запрещаем
		$targetUserId = ArrayHelper::getValue($params, 'userId');
		if ($targetUserId === null) {
			return false;
		}

		$currentUserIsMaster = $authManager->checkAccess($currentUserId, Rbac::ROLE_MASTER);
		$targetUserIsMaster = $authManager->checkAccess($targetUserId, Rbac::ROLE_MASTER);

		// Если хотят изменить мастера, и текущий пользователь сам не мастер, то запрещаем
		if ($targetUserIsMaster && !$currentUserIsMaster) {
			return false;
		}

		return true;
	}
}