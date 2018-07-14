<?php

namespace common\components;

use common\models\User;
use Yii;
use yii\base\Component;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;
use yii\rbac\Role;

/**
 * @author MrAnger
 */
class UserBuddy extends Component {
	/**
	 * @var array
	 */
	protected $roleList;

	/**
	 * @var array
	 */
	protected $roleDropdownList;

	/**
	 * @var DbManager
	 */
	protected $authManager;

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();

		$this->authManager = Yii::$app->authManager;
	}

	/**
	 * @param boolean $useCache
	 *
	 * @return array
	 */
	public function getRoleList($useCache = true) {
		if (!$useCache || $this->roleList === null) {
			$this->roleList = ArrayHelper::map($this->authManager->getRoles(), 'name', function (Role $item) {
				if (empty($item->description)) {
					return $item->name;
				}

				return $item->description;
			});
		}

		return $this->roleList;
	}

	/**
	 * @param boolean $useCache
	 *
	 * @return array
	 */
	public function getRoleDropdownList($useCache = true) {
		if (!$useCache || $this->roleDropdownList === null) {
			$roles = $this->getRoleList($useCache);

			$this->roleDropdownList = $this->getRoleList($useCache);
		}

		return $this->roleDropdownList;
	}

	/**
	 * @param $roleId
	 *
	 * @return ActiveQuery
	 */
	public function queryFindByRole($roleId) {
		$userIds = $this->authManager->getUserIdsByRole($roleId);

		if (!empty($userIds)) {
			return User::find()
				->where(['in', 'id', $userIds]);
		}

		// Если пользователей с такой ролью нет, надо всё равно вернуть ActiveQuery, но с заведомо пустым результатом
		return User::find()
			->where(['id' => -1]);
	}

	/**
	 * @return ActiveQuery
	 */
	public function queryUsersHasRoles() {
		$query = new Query();
		$query->from($this->authManager->assignmentTable)
			->select('user_id');

		return User::find()
			->where(['in', 'id', $query]);
	}

	/**
	 * @param $userId
	 *
	 * @return array
	 */
	public function getRolesForUser($userId) {
		return ArrayHelper::getColumn($this->authManager->getRolesByUser($userId), 'name', false);
	}

	/**
	 * @param $userId
	 *
	 * @return array
	 */
	public function getTranslatedRoleListForUser($userId) {
		$output = [];

		$roles = $this->getRolesForUser($userId);

		foreach ($roles as $roleId) {
			$output[$roleId] = ArrayHelper::getValue($this->getRoleList(), $roleId);
		}

		return $output;
	}

	/**
	 * @param string $baseName
	 *
	 * @return string
	 */
	public function generateUniqueUserName($baseName = null) {
		$generator = function () {
			return 'u-' . substr(md5(uniqid()), 25);
		};

		/** @var string $value */
		$value = $baseName !== null ? $baseName : $generator();
		$iteration = 0;

		while (($count = User::find()
				->where(['username' => $value])
				->count()) > 0) {
			$iteration++;

			$value = $generator();
		}

		return $value;
	}
}
