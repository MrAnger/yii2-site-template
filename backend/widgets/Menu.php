<?php

namespace backend\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class Menu extends \yii\widgets\Menu {
	/**
	 * @var string
	 */
	public $parentCssClass = 'parent';

	/**
	 * @var boolean
	 */
	public $activateParents = true;

	/**
	 * @var string
	 */
	public $countTemplate = '<span class="pull-right badge">{count}</span>';

	/**
	 * @var string
	 */
	public $labelTemplate = '<a href="#">{count}{icon}<span>{label}</span></a>';

	/**
	 * @var string
	 */
	public $linkTemplate = '<a href="{url}">{count}{icon}<span>{label}</span></a>';

	/**
	 * @var string
	 */
	public $submenuTemplate = "\n<ul class='children'>\n{items}\n</ul>\n";

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
		$countHtml = (ArrayHelper::getValue($item, 'count', 0) > 0 ? strtr($this->countTemplate, [
			'{count}' => ArrayHelper::getValue($item, 'count'),
		]) : "");

		if (isset($item['url'])) {
			$template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);

			return strtr($template, [
				'{url}'   => Html::encode(Url::to($item['url'])),
				'{label}' => $item['label'],
				'{icon}'  => ArrayHelper::getValue($item, 'icon', ''),
				'{count}' => $countHtml,
			]);
		} else {
			$template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);

			return strtr($template, [
				'{label}' => $item['label'],
				'{icon}'  => ArrayHelper::getValue($item, 'icon', ''),
				'{count}' => $countHtml,
			]);
		}
	}
}
