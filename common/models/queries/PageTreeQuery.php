<?php

namespace common\models\queries;

use creocoder\nestedsets\NestedSetsQueryBehavior;
use yii\db\ActiveQuery;

/**
 * @see \creocoder\nestedsets\NestedSetsQueryBehavior
 */
class PageTreeQuery extends ActiveQuery {
	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			NestedSetsQueryBehavior::className(),
		];
	}
}