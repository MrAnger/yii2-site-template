<?php

namespace console\controllers;

use common\Rbac;
use common\models\User;
use Yii;
use yii\base\ErrorException;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\rbac\Rule;

/**
 * @author MrAnger
 */
class RbacManagerController extends Controller {
	public function actionInitRoles() {
		$authManager = Yii::$app->authManager;

		$authManager->removeAll();

		foreach (Rbac::getPermissionList() as $permissionId) {
			$permission = $authManager->createPermission($permissionId);

			$ruleClass = ArrayHelper::getValue(Rbac::getRuleList(), $permissionId, false);
			if ($ruleClass !== false) {
				/** @var Rule $rule */
				$rule = Yii::createObject(['class' => $ruleClass]);
				$authManager->add($rule);

				$permission->ruleName = $rule->name;
			}

			$authManager->add($permission);
		}

		foreach (Rbac::$roleMap as $item) {
			$roleId = ArrayHelper::getValue($item, 'role');
			$permissions = ArrayHelper::getValue($item, 'permissions', []);

			$role = $authManager->createRole($roleId);
			$authManager->add($role);

			foreach ($permissions as $permissionId) {
				$permission = $authManager->getPermission($permissionId);

				$authManager->addChild($role, $permission);
			}
		}
	}

	/**
	 * @param string $email
	 */
	public function actionSetUserAsMaster($email) {
		$roleId = Rbac::ROLE_MASTER;

		try {
			if ($this->setRoleUser($email, $roleId))
				$this->log("Set user($email) as '$roleId' successfully.");
			else
				$this->log("Set user($email) as '$roleId' failed.");
		} catch (\Exception $e) {
			$this->log("ERROR: " . $e->getMessage());
		}
	}

	/**
	 * @param string $email
	 */
	public function actionUnsetUserAsMaster($email) {
		$roleId = Rbac::ROLE_MASTER;

		try {
			if ($this->removeRoleUser($email, $roleId))
				$this->log("Remove user($email) role '$roleId' successfully.");
			else
				$this->log("Remove user($email) role '$roleId' failed.");
		} catch (\Exception $e) {
			$this->log("ERROR: " . $e->getMessage());
		}
	}

	/**
	 * @param string $email
	 * @param string $roleId
	 *
	 * @return bool
	 *
	 * @throws \Exception
	 */
	private function setRoleUser($email, $roleId) {
		$user = $this->findUserByEmail($email);

		$authManager = Yii::$app->authManager;

		if ($authManager->checkAccess($user->id, $roleId))
			throw new \Exception("User with email '$email' already setted role '$roleId'.");

		$role = $authManager->getRole($roleId);

		if ($role !== null) {
			return $authManager->assign($role, $user->id);
		}

		return false;
	}

	/**
	 * @param string $email
	 * @param string $roleId
	 *
	 * @return bool
	 *
	 * @throws \Exception
	 */
	private function removeRoleUser($email, $roleId) {
		$user = $this->findUserByEmail($email);

		$authManager = Yii::$app->authManager;

		if (!$authManager->checkAccess($user->id, $roleId))
			throw new \Exception("User with email '$email' not setted role '$roleId'.");

		$role = $authManager->getRole($roleId);

		if ($role !== null) {
			return $authManager->revoke($role, $user->id);
		}

		return false;
	}

	/**
	 * @param string $email
	 *
	 * @return User
	 *
	 * @throws \Exception
	 */
	private function findUserByEmail($email) {
		$user = User::findOne(['email' => $email]);

		if ($user === null)
			throw new \Exception("User with email '$email' not found.");

		return $user;
	}

	/**
	 * @param string $string
	 */
	private function log($string) {
		echo $string . PHP_EOL;
	}
}