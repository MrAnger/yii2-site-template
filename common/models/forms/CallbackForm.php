<?php

namespace common\models\forms;

use Yii;
use yii\helpers\ArrayHelper;

class CallbackForm extends BaseFeedbackForm {
	public function init() {
		parent::init();

		$this->type = 1;
		$this->formDescription = "Форма заказа обратного звонка";

		$this->useCaptcha = false;
		$this->useAcceptPrivacy = false;
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return array_merge(parent::rules(), [
			[['phone'], 'required'],
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function formAttributes() {
		return [
			'formDescription',
			'phone',
			'message',
		];
	}
}
