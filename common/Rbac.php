<?php

namespace common;

use common\rules\SwitchIdentityRule;
use common\rules\UserBlockRule;
use common\rules\UserChangeRoleRule;
use common\rules\UserDeleteRule;
use common\rules\UserEditRule;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * @author MrAnger
 */
abstract class Rbac {
	const ROLE_MASTER = 'MASTER';
	const ROLE_ADMIN = 'ADMIN';

	const PERMISSION_CONTROL_PANEL_ACCESS = 'controlPanelAccess';
	const PERMISSION_USER_MANAGER_ACCESS = 'userManagerAccess';
	const PERMISSION_RBAC_MANAGER_ACCESS = 'rbacManagerAccess';
	const PERMISSION_SWITCH_IDENTITY_ACCESS = 'switchIdentityAccess';

	const PERMISSION_USER_EDIT = 'userEdit';
	const PERMISSION_USER_DELETE = 'userDelete';
	const PERMISSION_USER_BLOCK = 'userBlock';
	const PERMISSION_USER_ROLE_CHANGE = 'userRoleChange';

	protected static $roleMap = [
		[
			'role'        => self::ROLE_MASTER,
			'permissions' => [],// Мастер имеет все пермишены
		],
		[
			'role'        => self::ROLE_ADMIN,
			'permissions' => [
				self::PERMISSION_CONTROL_PANEL_ACCESS,
				self::PERMISSION_USER_MANAGER_ACCESS,
				self::PERMISSION_SWITCH_IDENTITY_ACCESS,

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
			self::PERMISSION_CONTROL_PANEL_ACCESS,
			self::PERMISSION_USER_MANAGER_ACCESS,
			self::PERMISSION_RBAC_MANAGER_ACCESS,
			self::PERMISSION_SWITCH_IDENTITY_ACCESS,

			self::PERMISSION_USER_EDIT,
			self::PERMISSION_USER_DELETE,
			self::PERMISSION_USER_BLOCK,
			self::PERMISSION_USER_ROLE_CHANGE,
		];
	}

	/**
	 * @return array
	 */
	public static function getRuleList() {
		return [
			static::PERMISSION_SWITCH_IDENTITY_ACCESS => SwitchIdentityRule::className(),

			static::PERMISSION_USER_EDIT        => UserEditRule::className(),
			static::PERMISSION_USER_DELETE      => UserDeleteRule::className(),
			static::PERMISSION_USER_BLOCK       => UserBlockRule::className(),
			static::PERMISSION_USER_ROLE_CHANGE => UserChangeRoleRule::className(),
		];
	}

	/**
	 * @return array
	 */
	public static function getRoleMap() {
		$output = self::$roleMap;

		foreach ($output as &$item) {
			if ($item['role'] == self::ROLE_MASTER) {
				$item['permissions'] = self::getPermissionList();

				unset($item);

				break;
			}

			unset($item);
		}

		return $output;
	}
}