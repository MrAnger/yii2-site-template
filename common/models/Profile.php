<?php

namespace common\models;

use Yii;

class Profile extends \Da\User\Model\Profile {
	public function rules() {
		return array_merge(parent::rules(), [
			[['name', 'public_email', 'gravatar_email', 'location', 'website'], 'trim'],
			[['name', 'public_email', 'gravatar_email', 'location', 'website'], 'default'],
		]);
	}
} 