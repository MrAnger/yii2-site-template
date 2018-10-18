<?php

namespace common\models;

use himiklab\sitemap\behaviors\SitemapBehavior;
use MrAnger\Yii2_ImageManager\models\Image;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;

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
 * @property int $is_show_sitemap
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $layout
 * @property string $file_template
 * @property string $params
 * @property string $published_at
 * @property string $redirect_url
 * @property integer $redirect_code
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Image $imageCover
 * @property PageTree $tree
 * @property PageGalleryImage[] $galleryImageLinks
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
			'sitemap'    => [
				'class'       => SitemapBehavior::className(),
				'scope'       => function ($query) {
					/** @var \yii\db\ActiveQuery $query */
					$query->andWhere([
						'AND',
						['<>', 'slug', 'root'],
						['=', 'is_enabled', 1],
						['=', 'is_show_sitemap', 1],
					]);
				},
				'dataClosure' => function (Page $model) {
					$url = Url::to(['/site/view-page-by-slug', 'slug' => $model->slug], true);

					if ($model->slug == 'index') {
						$url = Url::to(['/site/index'], true);
					}

					return [
						'loc'        => $url,
						'lastmod'    => strtotime($model->updated_at),
						'changefreq' => SitemapBehavior::CHANGEFREQ_WEEKLY,
						'priority'   => 0.8,
					];
				},
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
			[['image_cover_id', 'redirect_code'], 'integer'],
			[['published_at', 'created_at', 'updated_at'], 'safe'],
			[['name'], 'string', 'max' => 250],
			[['slug', 'meta_title', 'meta_description', 'meta_keywords', 'layout', 'file_template'], 'string', 'max' => 255],
			[['redirect_url'], 'string', 'max' => 1000],
			[['image_cover_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['image_cover_id' => 'id']],

			[['is_enabled', 'is_show_sitemap'], 'boolean'],
			[['slug'], 'unique'],

			[['name', 'slug', 'intro', 'content', 'params', 'published_at', 'meta_title', 'meta_description', 'meta_keywords', 'layout', 'file_template', 'redirect_url'], 'trim'],
			[['name', 'slug', 'intro', 'content', 'params', 'published_at', 'meta_title', 'meta_description', 'meta_keywords', 'layout', 'file_template', 'redirect_url'], 'default'],
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
			'is_show_sitemap'  => 'Отображать в карте сайта',
			'meta_title'       => 'Title',
			'meta_description' => 'Meta description',
			'meta_keywords'    => 'Meta keywords',
			'layout'           => 'Layout',
			'file_template'    => 'Файл шаблона',
			'params'           => 'Дополнительные параметры',
			'published_at'     => 'Опубликовано',
			'redirect_url'     => 'Редирект на указанный URL',
			'redirect_code'    => 'Код редиректа',
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
	 * @return \yii\db\ActiveQuery
	 */
	public function getGalleryImageLinks() {
		return $this->hasMany(PageGalleryImage::className(), ['page_id' => 'id'])
			->joinWith('image')
			->orderBy([
				PageGalleryImage::tableName() . ".sort_order" => SORT_ASC,
			]);
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

				if (empty($data[0])) {
					break;
				}

				$resultArray[$data[0]] = ((isset($data[1])) ? $data[1] : null);
			}

			$this->params = Json::encode($resultArray);
		}

		return $this->params;
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function setParam($key, $value) {
		$array = $this->paramsAsArray;

		$array[$key] = $value;

		$this->params = Json::encode($array);
	}

	/**
	 * @param string $key
	 * @param mixed $defaultValue
	 *
	 * @return mixed
	 */
	public function getParam($key, $defaultValue = null) {
		return ArrayHelper::getValue($this->paramsAsArray, $key, $defaultValue);
	}

	/**
	 * @return array
	 */
	public static function getHelpParams() {
		return [];
	}
}
