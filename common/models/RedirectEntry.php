<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%redirect_entry}}".
 *
 * @property string $id
 * @property string $from
 * @property string $to
 * @property int $code
 */
class RedirectEntry extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%redirect_entry}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['from', 'to', 'code'], 'required'],
			[['from', 'to'], 'string', 'max' => 1000],
			[['code'], 'integer'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id'   => 'ID',
			'from' => 'Со страницы',
			'to'   => 'На страницу',
			'code' => 'Код редиректа',
		];
	}
}
