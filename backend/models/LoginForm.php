<?php

namespace backend\models;

use dektrium\user\Finder;
use dektrium\user\helpers\Password;
use Yii;
use yii\base\Model;
use dektrium\user\traits\ModuleTrait;

/**
 * @author MrAnger
 */
class LoginForm extends \dektrium\user\models\LoginForm {
	/** @inheritdoc */
	public function rules() {
		return [
			'requiredFields'       => [['login', 'password'], 'required'],
			'loginTrim'            => ['login', 'trim'],
			'passwordValidate'     => [
				'password',
				function ($attribute) {
					if ($this->user === null || !Password::validate($this->password, $this->user->password_hash)) {
						$this->addError($attribute, Yii::t('user', 'Invalid login or password'));
					}

					if ($this->user !== null) {
						$authManager = Yii::$app->authManager;

						$roles = $authManager->getRolesByUser($this->user->id);

						if (count($roles) == 0)
							$this->addError('login', Yii::t('app.errors', 'You are not allowed to enter control panel.'));
					}
				},
			],
			'confirmationValidate' => [
				'login',
				function ($attribute) {
					if ($this->user !== null) {
						$confirmationRequired = $this->module->enableConfirmation
							&& !$this->module->enableUnconfirmedLogin;
						if ($confirmationRequired && !$this->user->getIsConfirmed()) {
							$this->addError($attribute, Yii::t('user', 'You need to confirm your email address'));
						}
						if ($this->user->getIsBlocked()) {
							$this->addError($attribute, Yii::t('user', 'Your account has been blocked'));
						}
					}
				},
			],
			'rememberMe'           => ['rememberMe', 'boolean'],
		];
	}
}
