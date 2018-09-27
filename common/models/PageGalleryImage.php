<?php

namespace common\models;

use MrAnger\Yii2_ImageManager\models\Image;
use Yii;
use yii2tech\ar\position\PositionBehavior;

/**
 * This is the model class for table "{{%page_image_gallery}}".
 *
 * @property string $id
 * @property string $page_id
 * @property string $image_id
 * @property int $sort_order
 *
 * @property Image $image
 * @property Page $page
 */
class PageGalleryImage extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'positionBehavior' => [
				'class'             => PositionBehavior::className(),
				'positionAttribute' => 'sort_order',
				'groupAttributes'   => [
					'page_id',
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%page_image_gallery}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['page_id', 'image_id'], 'required'],
			[['page_id', 'image_id', 'sort_order'], 'integer'],
			[['image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['image_id' => 'id']],
			[['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => Page::className(), 'targetAttribute' => ['page_id' => 'id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id'         => 'ID',
			'page_id'    => 'Страница',
			'image_id'   => 'Изображение',
			'sort_order' => 'Порядок',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getImage() {
		return $this->hasOne(Image::className(), ['id' => 'image_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPage() {
		return $this->hasOne(Page::className(), ['id' => 'page_id']);
	}
}
