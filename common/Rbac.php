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

	public static $roleMap = [
		[
			'role'        => self::ROLE_MASTER,
			'permissions' => [self::ADMIN_ACCESS],
		],
	];

	/**
	 * @return array
	 */
	public static function getRoleList() {
		return [
			static::ROLE_MASTER,
		];
	}
}