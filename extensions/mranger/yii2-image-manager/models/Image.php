<?php

namespace MrAnger\Yii2_ImageManager\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%image}}".
 *
 * @property string $id
 * @property string $file
 * @property string $title
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 */
class Image extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%image}}';
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'timestamps' => [
				'class' => TimestampBehavior::class,
				'value' => new Expression('NOW()'),
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['file'], 'required'],
			[['description'], 'string'],
			[['created_at', 'updated_at'], 'safe'],
			[['file'], 'string', 'max' => 1024],
			[['title'], 'string', 'max' => 255],

			[['file', 'title', 'description'], 'trim'],
			[['file', 'title', 'description'], 'default'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id'          => 'ID',
			'file'        => 'Путь',
			'title'       => 'Заголовок',
			'description' => 'Описание',
			'created_at'  => 'Создан',
			'updated_at'  => 'Изменен',
		];
	}
}
