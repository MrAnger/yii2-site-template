<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%block}}".
 *
 * @property string $id
 * @property string $name
 * @property string $code
 * @property string $content
 * @property string $created_at
 * @property string $updated_at
 */
class Block extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'timestamps' => [
				'class' => TimestampBehavior::class,
				'value' => new Expression('NOW()'),
			],
			[
				'class'         => SluggableBehavior::class,
				'attribute'     => 'name',
				'slugAttribute' => 'code',
				'immutable'     => true,
				'ensureUnique'  => true,
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%block}}';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			[['name', 'content'], 'required'],
			[['content'], 'string'],
			[['created_at', 'updated_at'], 'safe'],
			[['name', 'code'], 'string', 'max' => 255],
			[['code'], 'unique'],

			[['name', 'code', 'content'], 'trim'],
			[['name', 'code', 'content'], 'default'],

			[['code'], 'match', 'pattern' => '/^[\w\-_]*$/i'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'id'         => 'ID',
			'name'       => 'Название',
			'code'       => 'Код',
			'content'    => 'Содержимое',
			'created_at' => 'Создан',
			'updated_at' => 'Изменен',
		];
	}
}
