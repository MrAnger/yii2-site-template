<?php

namespace common\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class Menu extends \yii\widgets\Menu {
	public $parentCssClass = 'parent';
	public $activateParents = true;

	protected function renderItems($items) {
		$n = count($items);
		$lines = [];
		foreach ($items as $i => $item) {
			$options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
			$tag = ArrayHelper::remove($options, 'tag', 'li');
			$class = [];
			if (isset($item['items']) && !empty($item['items'])) {
				$class[] = $this->parentCssClass;
			}
			if ($item['active']) {
				$class[] = $this->activeCssClass;
			}
			if ($i === 0 && $this->firstItemCssClass !== null) {
				$class[] = $this->firstItemCssClass;
			}
			if ($i === $n - 1 && $this->lastItemCssClass !== null) {
				$class[] = $this->lastItemCssClass;
			}
			if (!empty($class)) {
				if (empty($options['class'])) {
					$options['class'] = implode(' ', $class);
				} else {
					$options['class'] .= ' ' . implode(' ', $class);
				}
			}

			$menu = $this->renderItem($item);
			if (!empty($item['items'])) {
				$submenuTemplate = ArrayHelper::getValue($item, 'submenuTemplate', $this->submenuTemplate);
				$menu .= strtr($submenuTemplate, [
					'{items}' => $this->renderItems($item['items']),
				]);
			}
			$lines[] = Html::tag($tag, $menu, $options);
		}

		return implode("\n", $lines);
	}

	protected function renderItem($item) {
		if (isset($item['url'])) {
			$template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);

			return strtr($template, [
				'{url}'   => Html::encode(Url::to($item['url'])),
				'{label}' => $item['label'],
				'{icon}'  => ArrayHelper::getValue($item, 'icon', ''),
			]);
		} else {
			$template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);

			return strtr($template, [
				'{label}' => $item['label'],
				'{icon}'  => ArrayHelper::getValue($item, 'icon', ''),
			]);
		}
	}
}
