<?php

namespace common\components;

use common\models\User;
use Yii;
use yii\base\Component;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;

/**
 * @author Cyrill Tekord
 */
class UserBuddy extends Component {
	/**
	 * @var string
	 */
	public $workingModule = 'user';

	/**
	 * @var array
	 */
	protected $_roleList;

	/**
	 * @var array
	 */
	protected $_roleDropdownList;

	/**
	 * @var DbManager
	 */
	protected $_authManager;

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();

		$this->_authManager = Yii::$app->getAuthManager();

		if (is_string($this->workingModule))
			$this->workingModule = Yii::$app->getModule($this->workingModule);
	}

	/**
	 * @param boolean $useCache
	 *
	 * @return array
	 */
	public function getRoleList($useCache = true) {
		if (!$useCache || $this->_roleList === null) {
			$this->_roleList = ArrayHelper::getColumn($this->_authManager->getRoles(), 'name');
		}

		return $this->_roleList;
	}

	/**
	 * @param boolean $useCache
	 *
	 * @return array
	 */
	public function getRoleDropdownList($useCache = true) {
		if (!$useCache || $this->_roleDropdownList === null) {
			$roleList = $this->getRoleList($useCache);

			$roles = ArrayHelper::getColumn($this->_authManager->getRoles(), 'name');

			$this->_roleDropdownList = [];

			foreach ($roles as $roleId) {
				$this->_roleDropdownList[$roleId] = Yii::t('app.roles', $roleId);
			}
		}

		return $this->_roleDropdownList;
	}

	/**
	 * @param $roleId
	 *
	 * @return ActiveQuery
	 */
	public function findByRole($roleId) {
		$query = new Query();
		$query->from($this->_authManager->assignmentTable)
			->select('user_id')
			->where(['item_name' => $roleId]);

		return User::find()
			->where(['in', 'id', $query]);
	}

	/**
	 * @return ActiveQuery
	 */
	public function getQueryUsersHasRoles() {
		$query = new Query();
		$query->from($this->_authManager->assignmentTable)
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
		return ArrayHelper::getColumn($this->_authManager->getAssignments($userId), 'roleName');
	}

	/**
	 * @param $userId
	 *
	 * @return array
	 */
	public function getTranslatedRoleListForUser($userId) {
		$roles = $this->getRolesForUser($userId);

		foreach ($roles as &$role) {
			$role = Yii::t('app.roles', $role);
		}

		if (count($roles) == 0)
			$roles[] = Yii::t('app.users', 'User');

		return $roles;
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

	/**
	 * Генерирует хеш переданного пароля со сложностью, определяемой параметром модуля.
	 *
	 * @param $password
	 *
	 * @return string
	 */
	public function generatePasswordHash($password) {
		return Yii::$app->security->generatePasswordHash($password, $this->workingModule->cost);
	}

	/**
	 * @param $email
	 *
	 * @return string
	 */
	public function generateEmailHash($email) {
		return md5($email . uniqid());
	}
}
