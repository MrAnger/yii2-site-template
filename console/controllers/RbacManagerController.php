<?php

namespace console\controllers;

use common\Rbac;
use common\models\User;
use Yii;
use yii\base\ErrorException;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\rbac\ManagerInterface;
use yii\rbac\Permission;
use yii\rbac\Role;
use yii\rbac\Rule;

/**
 * @author MrAnger
 */
class RbacManagerController extends Controller {
	/**
	 * @var ManagerInterface
	 */
	private $authManager;

	public function init() {
		parent::init();

		$this->authManager = Yii::$app->authManager;
	}

	/**
	 * Данный метод удаляет все данные Rbac и создает новую структуру ролей в соответствии с настройками в
	 * common\Rbac::roleMap
	 */
	public function actionInitRoles() {
		$this->authManager->removeAll();

		foreach (Rbac::getPermissionList() as $permissionId) {
			$permission = $this->authManager->createPermission($permissionId);

			// Описание
			$description = Yii::t('app.permissions', $permissionId);
			if ($description != $permissionId) {
				$permission->description = $description;
			}

			$ruleClass = ArrayHelper::getValue(Rbac::getRuleList(), $permissionId);
			if ($ruleClass !== null) {
				/** @var Rule $rule */
				$rule = Yii::createObject(['class' => $ruleClass]);
				$this->authManager->add($rule);

				$permission->ruleName = $rule->name;
			}

			$this->authManager->add($permission);
		}

		foreach (Rbac::getRoleMap() as $item) {
			$roleId = ArrayHelper::getValue($item, 'role');
			$permissions = ArrayHelper::getValue($item, 'permissions', []);

			$role = $this->authManager->createRole($roleId);

			// Описание
			$description = Yii::t('app.roles', $roleId);
			if ($description != $roleId) {
				$role->description = $description;
			}

			$this->authManager->add($role);

			foreach ($permissions as $permissionId) {
				$permission = $this->authManager->getPermission($permissionId);

				$this->authManager->addChild($role, $permission);
			}
		}
	}

	/**
	 * Данный метод ничего не удаляет, а лишь создает недостоющие элементы в соответствии с настройками в
	 * common\Rbac::roleMap
	 */
	public function actionUpdateRoles() {
		foreach (Rbac::getPermissionList() as $permissionId) {
			$permission = $this->authManager->getPermission($permissionId);

			if ($permission === null) {
				$permission = $this->authManager->createPermission($permissionId);
			}

			// Описание
			$description = Yii::t('app.permissions', $permissionId);
			if ($description != $permissionId && $permission->description === null) {
				$permission->description = $description;
			}

			$ruleClass = ArrayHelper::getValue(Rbac::getRuleList(), $permissionId);
			if ($ruleClass !== null) {
				/** @var Rule $rule */
				$rule = Yii::createObject(['class' => $ruleClass]);

				$oldRule = $this->authManager->getRule($rule->name);
				if ($oldRule === null) {
					$this->authManager->add($rule);
				} else {
					$this->authManager->update($rule->name, $rule);
				}

				$permission->ruleName = $rule->name;
			}

			if ($permission->createdAt === null) {
				$this->authManager->add($permission);
			} else {
				$this->authManager->update($permission->name, $permission);
			}
		}

		foreach (Rbac::getRoleMap() as $item) {
			$roleId = ArrayHelper::getValue($item, 'role');
			$permissions = ArrayHelper::getValue($item, 'permissions', []);

			$role = $this->authManager->getRole($roleId);
			if ($role === null) {
				$role = $this->authManager->createRole($roleId);
			}

			// Описание
			$description = Yii::t('app.roles', $roleId);
			if ($description != $roleId && $role->description === null) {
				$role->description = $description;
			}

			if ($role->createdAt === null) {
				$this->authManager->add($role);
			} else {
				$this->authManager->update($role->name, $role);
			}

			foreach ($permissions as $permissionId) {
				$permission = $this->authManager->getPermission($permissionId);

				if (!$this->authManager->hasChild($role, $permission)) {
					$this->authManager->addChild($role, $permission);
				}
			}
		}
	}

	public function actionUserAssignRole($email, $roleId) {
		$user = $this->findUserByEmail($email);

		if ($this->authManager->checkAccess($user->id, $roleId)) {
			$this->stderr("User with email $email already assigned role $roleId.");

			return true;
		}

		$role = $this->authManager->getRole($roleId);

		if ($role === null) {
			$this->stderr("Role $roleId not found.");

			return true;
		}

		$result = $this->authManager->assign($role, $user->id);

		if ($result) {
			$this->stdout("User with email $email assigned to $roleId successfully.");
		} else {
			$this->stdout("User with email $email assigned to $roleId failed.");
		}
	}

	public function actionUserRevokeRole($email, $roleId) {
		$user = $this->findUserByEmail($email);

		if (!$this->authManager->checkAccess($user->id, $roleId)) {
			$this->stderr("User with email $email not assigned role $roleId.");

			return true;
		}

		$role = $this->authManager->getRole($roleId);

		if ($role === null) {
			$this->stderr("Role $roleId not found.");

			return true;
		}

		$result = $this->authManager->revoke($role, $user->id);

		if ($result) {
			$this->stdout("User with email $email revoke $roleId successfully.");
		} else {
			$this->stdout("User with email $email revoke $roleId failed.");
		}
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
}