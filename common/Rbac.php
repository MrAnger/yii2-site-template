<?php

namespace common;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * @author MrAnger
 */
abstract class Rbac {
	/**
	 * @const
	 */
	const ROLE_MASTER = 'MASTER';

	const ADMIN_ACCESS = 'adminAccess';

	/**
	 * @return array
	 */
	public static function getRoleList() {
		return [
			static::ROLE_MASTER,
		];
	}
}