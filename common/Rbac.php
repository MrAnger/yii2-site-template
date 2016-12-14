<?php

namespace common;

use common\rules\UserBlockRule;
use common\rules\UserDeleteRule;
use common\rules\UserEditRule;
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
	const ROLE_ADMIN = 'ADMIN';

	/**
	 * @const
	 */
	const PERMISSION_USER_EDIT = 'permissionUserEdit';
	const PERMISSION_USER_DELETE = 'permissionUserDelete';
	const PERMISSION_USER_BLOCK = 'permissionUserBlock';

	const ADMIN_ACCESS = 'adminAccess';

	public static $roleMap = [
		[
			'role'        => self::ROLE_MASTER,
			'permissions' => [
				self::ADMIN_ACCESS,
				self::PERMISSION_USER_EDIT,
				self::PERMISSION_USER_DELETE,
				self::PERMISSION_USER_BLOCK,
			],
		],
		[
			'role'        => self::ROLE_ADMIN,
			'permissions' => [
				self::ADMIN_ACCESS,
				self::PERMISSION_USER_EDIT,
				self::PERMISSION_USER_DELETE,
				self::PERMISSION_USER_BLOCK,
			],
		],
	];

	/**
	 * @return array
	 */
	public static function getRoleList() {
		return [
			static::ROLE_MASTER,
			static::ROLE_ADMIN,
		];
	}

	/**
	 * @return array
	 */
	public static function getPermissionList() {
		return [
			self::ADMIN_ACCESS,
			static::PERMISSION_USER_EDIT,
			static::PERMISSION_USER_DELETE,
			static::PERMISSION_USER_BLOCK,
		];
	}

	/**
	 * @return array
	 */
	public static function getRuleList() {
		return [
			static::PERMISSION_USER_EDIT   => UserEditRule::className(),
			static::PERMISSION_USER_DELETE => UserDeleteRule::className(),
			static::PERMISSION_USER_BLOCK  => UserBlockRule::className(),
		];
	}
}