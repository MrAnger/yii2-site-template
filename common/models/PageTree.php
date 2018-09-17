<?php

namespace common\models;

use common\models\queries\PageTreeQuery;
use creocoder\nestedsets\NestedSetsBehavior;
use creocoder\nestedsets\NestedSetsQueryBehavior;
use Yii;

/**
 * This is the model class for table "{{%page_tree}}".
 *
 * @property string $page_id
 * @property int $tree
 * @property int $left_node
 * @property int $right_node
 * @property int $depth
 *
 * @property Page $page
 */
class PageTree extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'tree' => [
				'class'          => NestedSetsBehavior::className(),
				'treeAttribute'  => 'tree',
				'leftAttribute'  => 'left',
				'rightAttribute' => 'right',
				'depthAttribute' => 'depth',
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function transactions() {
		return [
			self::SCENARIO_DEFAULT => self::OP_ALL,
		];
	}

	/**
	 * @inheritdoc
	 */
	public static function find() {
		return new PageTreeQuery(get_called_class());
	}

	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return '{{%page_tree}}';
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['page_id'], 'required'],
			[['tree', 'left', 'right', 'depth'], 'integer'],
			[['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => Page::className(), 'targetAttribute' => ['page_id' => 'id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'page_id'    => 'Page ID',
			'tree'       => 'Tree',
			'left_node'  => 'Left Node',
			'right_node' => 'Right Node',
			'depth'      => 'Depth',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPage() {
		return $this->hasOne(Page::className(), ['id' => 'page_id']);
	}
}
