<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%block}}".
 *
 * @property string $id
 * @property string $name
 * @property string $code
 * @property string $content
 * @property string $params
 * @property string $created_at
 * @property string $updated_at
 *
 * @property array $paramsAsArray
 * @property string $paramsAsString
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
			[['name'], 'required'],
			[['content', 'params'], 'string'],
			[['created_at', 'updated_at'], 'safe'],
			[['name', 'code'], 'string', 'max' => 255],
			[['code'], 'unique'],

			[['name', 'code', 'content', 'params'], 'trim'],
			[['name', 'code', 'content', 'params'], 'default'],

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
			'params'     => 'Дополнительные параметры',
			'created_at' => 'Создан',
			'updated_at' => 'Изменен',
		];
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
}
