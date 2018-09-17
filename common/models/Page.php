<?php

namespace common\models;

use MrAnger\Yii2_ImageManager\models\Image;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property string $id
 * @property string $name
 * @property string $slug
 * @property string $intro
 * @property string $content
 * @property string $image_cover_id
 * @property int $is_enabled
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $layout
 * @property string $file_template
 * @property string $params
 * @property string $published_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Image $imageCover
 * @property PageTree $tree
 *
 * @property array $paramsAsArray
 * @property string $paramsAsString
 */
class Page extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'timestamps' => [
				'class' => TimestampBehavior::className(),
				'value' => new Expression('NOW()'),
			],
			'sluggable'  => [
				'class'         => SluggableBehavior::className(),
				'attribute'     => 'name',
				'slugAttribute' => 'slug',
				'immutable'     => true,
				'ensureUnique'  => true,
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%page}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['name'], 'required'],
			[['intro', 'content', 'params'], 'string'],
			[['image_cover_id'], 'integer'],
			[['published_at', 'created_at', 'updated_at'], 'safe'],
			[['name'], 'string', 'max' => 250],
			[['slug', 'meta_title', 'meta_description', 'meta_keywords', 'layout', 'file_template'], 'string', 'max' => 255],
			[['image_cover_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['image_cover_id' => 'id']],

			[['is_enabled'], 'boolean'],

			[['name', 'slug', 'intro', 'content', 'params', 'published_at', 'meta_title', 'meta_description', 'meta_keywords', 'layout', 'file_template'], 'trim'],
			[['name', 'slug', 'intro', 'content', 'params', 'published_at', 'meta_title', 'meta_description', 'meta_keywords', 'layout', 'file_template'], 'default'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id'               => 'ID',
			'name'             => 'Название',
			'slug'             => 'URL',
			'intro'            => 'Краткое описание',
			'content'          => 'Основное содержание',
			'image_cover_id'   => 'Изображение',
			'is_enabled'       => 'Включен',
			'meta_title'       => 'Title',
			'meta_description' => 'Meta description',
			'meta_keywords'    => 'Meta keywords',
			'layout'           => 'Layout',
			'file_template'    => 'Файл шаблона',
			'params'           => 'Дополнительные параметры',
			'published_at'     => 'Опубликовано',
			'created_at'       => 'Создано',
			'updated_at'       => 'Изменено',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getImageCover() {
		return $this->hasOne(Image::className(), ['id' => 'image_cover_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTree() {
		return $this->hasOne(PageTree::className(), ['page_id' => 'id']);
	}

	/**
	 * @return array
	 */
	public function getParamsAsArray() {
		$raw = $this->params;

		if (empty($raw)) {
			return [];
		}

		return Json::decode($raw);
	}

	/**
	 * @return string
	 */
	public function getParamsAsString() {
		$array = $this->getParamsAsArray();

		if (empty($array)) {
			return null;
		}

		$output = "";

		foreach ($array as $key => $value) {
			$output .= "$key=$value\r\n";
		}

		return $output;
	}

	/**
	 * @return string
	 */
	public function convertParamsToJSON() {
		$raw = trim($this->params);

		if (empty($raw)) {
			$this->params = null;
		} else {
			$resultArray = [];

			foreach (explode("\r\n", $raw) as $line) {
				$data = explode('=', trim($line));

				if(empty($data[0])) {
					break;
				}

				$resultArray[$data[0]] = ((isset($data[1])) ? $data[1] : null);
			}

			$this->params = Json::encode($resultArray);
		}

		return $this->params;
	}
}
