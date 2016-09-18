<?php

namespace backend\models;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class UserSearch extends User {
	/**
	 * @inheritdoc
	 */
	public function scenarios() {
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * @param array $params
	 * @param array $overriddenParams
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params, $overriddenParams = []) {
		$query = User::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		$this->setAttributes($overriddenParams, false);

		$query->andFilterWhere([
			'email'    => $this->email,
			'username' => $this->username,
		]);

		return $dataProvider;
	}
}
