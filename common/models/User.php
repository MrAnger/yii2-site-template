<?php

namespace common\models;

use common\helpers\MailHelper;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * @property string $displayName
 * @property Author $author
 */
class User extends \Da\User\Model\User {
	/** @var array */
	public $roles = [];

	public function rules() {
		return array_merge(parent::rules(), [
			[['roles'], 'safe'],
		]);
	}

	public function attributeLabels() {
		return array_merge(parent::attributeLabels(), [
			'roles' => Yii::t('app.roles', 'Roles'),
		]);
	}

	public function loadRoles() {
		/** @var \common\components\UserBuddy $userBuddy */
		$userBuddy = Yii::$app->userBuddy;

		$this->roles = $userBuddy->getRolesForUser($this->id);
	}

	/**
	 * @param string $from
	 * @param string $subject
	 * @param string $message
	 *
	 * @return bool
	 */
	public function sendMailWithText($from, $subject, $message) {
		return MailHelper::sendMailWithText($from, $this->email, $subject, $message);
	}

	/**
	 * @param string $from
	 * @param string $subject
	 * @param string $view
	 * @param array $params
	 *
	 * @return bool
	 */
	public function sendMail($from, $subject, $view, $params = []) {
		return MailHelper::sendMail($from, $this->email, $subject, $view, $params);
	}

	public function assignRoles() {
		$authManager = Yii::$app->authManager;

		$authManager->revokeAll($this->id);

		if (empty($this->roles))
			return;

		foreach ($this->roles as $roleId) {
			$role = $authManager->getRole($roleId);

			if ($role !== null) {
				$authManager->assign($role, $this->id);
			}
		}
	}

	public function getDisplayName() {
		$profile = $this->profile;

		if ($profile !== null) {
			if ($profile->name !== null && strlen($profile->name) > 0)
				return $profile->name;
		}

		return $this->username;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAuthor() {
		return $this->hasOne(Author::class, ['user_id' => 'id']);
	}
}