<?php

namespace common\validators;

use common\models\Page;
use common\models\PageTree;
use Yii;
use yii\helpers\ArrayHelper;
use yii\validators\Validator;

class PageSlugUniqueValidator extends Validator {
	/**
	 * {@inheritdoc}
	 */
	public function validateAttribute($model, $attribute) {
		/** @var Page $model */

		/** @var PageTree $parentPageTree */
		if ($model->isNewRecord) {
			/** @var Page $parentPage */
			$parentPage = Page::find()
				->joinWith('tree')
				->where(['id' => $model->parentPageId])
				->one();

			$parentPageTree = $parentPage->tree;
		} else {
			$parentPageTree = $model->tree->parents(1)
				->joinWith('page')
				->one();
		}

		/** @var PageTree[] $childrenPageTreeList */
		$childrenPageTreeList = $parentPageTree->children(1)
			->joinWith('page')
			->all();

		$slugMap = ArrayHelper::map($childrenPageTreeList, 'page.id', 'page.slug');

		$index = array_search($model->{$attribute}, $slugMap);

		if ($index === false || $index == $model->id) {
			return true;
		}

		$this->addError($model, $attribute, "{attributeLabel} '{attributeValue}' уже занято.", [
			'attributeLabel' => $model->getAttributeLabel($attribute),
			'attributeValue' => $model->{$attribute},
		]);
	}
}
