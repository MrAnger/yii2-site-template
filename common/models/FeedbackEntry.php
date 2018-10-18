<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%feedback_entry}}".
 *
 * @property string $id
 * @property int $is_viewed
 * @property string $text
 * @property string $created_at
 */
class FeedbackEntry extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'timestamps' => [
				'class'              => TimestampBehavior::className(),
				'value'              => new Expression('NOW()'),
				'updatedAtAttribute' => false,
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%feedback_entry}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['is_viewed'], 'integer'],
			[['text'], 'required'],
			[['text'], 'string'],
			[['created_at'], 'safe'],

			[['text'], 'default'],
			[['text'], 'trim'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id'         => 'ID',
			'is_viewed'  => 'Просмотрено',
			'text'       => 'Текст',
			'created_at' => 'Дата обращения',
		];
	}
}
