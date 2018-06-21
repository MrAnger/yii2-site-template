<?php

namespace backend\models;

use common\Rbac;
use Da\User\Validator\TwoFactorCodeValidator;
use Yii;
use yii\base\Model;

/**
 * @author MrAnger
 */
class LoginForm extends \Da\User\Form\LoginForm {
	/** @inheritdoc */
	public function rules() {
		return [
			'requiredFields' => [['login', 'password'], 'required'],
			'requiredFieldsTwoFactor' => [
				['login', 'password', 'twoFactorAuthenticationCode'],
				'required',
				'on' => '2fa'
			],
			'loginTrim' => ['login', 'trim'],
			'twoFactorAuthenticationCodeTrim' => ['twoFactorAuthenticationCode', 'trim'],
			'passwordValidate' => [
				'password',
				function ($attribute) {
					if ($this->user === null ||
						!$this->securityHelper->validatePassword($this->password, $this->user->password_hash)
					) {
						$this->addError($attribute, Yii::t('usuario', 'Invalid login or password'));

						return true;
					}

					if ($this->user !== null) {
						$authManager = Yii::$app->authManager;

						if (!$authManager->checkAccess($this->user->id, Rbac::ADMIN_ACCESS))
							$this->addError('login', Yii::t('app.errors', 'You are not allowed to enter control panel.'));
					}
				},
			],
			'twoFactorAuthenticationCodeValidate' => [
				'twoFactorAuthenticationCode',
				function ($attribute) {
					if ($this->user === null ||
						!(new TwoFactorCodeValidator(
							$this->user,
							$this->twoFactorAuthenticationCode,
							$this->module->twoFactorAuthenticationCycles
						))
							->validate()) {
						$this->addError($attribute, Yii::t('usuario', 'Invalid two factor authentication code'));
					}
				}
			],
			'confirmationValidate' => [
				'login',
				function ($attribute) {
					if ($this->user !== null) {
						$module = $this->getModule();
						$confirmationRequired = $module->enableEmailConfirmation && !$module->allowUnconfirmedEmailLogin;
						if ($confirmationRequired && !$this->user->getIsConfirmed()) {
							$this->addError($attribute, Yii::t('usuario', 'You need to confirm your email address'));
						}
						if ($this->user->getIsBlocked()) {
							$this->addError($attribute, Yii::t('usuario', 'Your account has been blocked'));
						}
					}
				},
			],
			'rememberMe' => ['rememberMe', 'boolean'],
		];
	}
}
