<?php

namespace backend\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class PagesMenu extends \yii\widgets\Menu {
	/**
	 * @var string
	 */
	public $parentCssClass = 'parent';

	/**
	 * @var string
	 */
	public $activeCssClass = 'active open';

	/**
	 * @var boolean
	 */
	public $activateParents = true;

	/**
	 * @var string
	 */
	public $linkTemplate = <<<HTML
<div class='item' data-id="{id}">
	<a class='link' href='{url}' title="{label}">{label}</a>
	<small class="frontend-url">{frontendUrl}</small>
	<span class='actions'>
		<a href='{urlCreate}' title="Создать подстраницу">
			<i class='glyphicon glyphicon-plus' aria-hidden='true'></i>
		</a>
		<a href='{urlDelete}' title="Удалить" data-method="post" data-confirm="Вы точно хотите удалить эту страницу? Так же удалятся все подстраницы текущей страницы.">
			<i class='glyphicon glyphicon-trash' aria-hidden='true'></i>
		</a>
	</span>
</div>
HTML;

	public $submenuTemplate = "\n<ul class='js-draggable-container'>\n{items}\n</ul>\n";

	public $options = [
		'class' => 'pages-menu js-draggable-container',
	];

	/**
	 * @inheritdoc
	 */
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
			Html::addCssClass($options, $class);

			$menu = $this->renderItem($item);

			$submenuTemplate = ArrayHelper::getValue($item, 'submenuTemplate', $this->submenuTemplate);
			$menu .= strtr($submenuTemplate, [
				'{items}' => $this->renderItems(ArrayHelper::getValue($item, 'items', [])),
			]);

			$lines[] = Html::tag($tag, $menu, $options);
		}

		return implode("\n", $lines);
	}

	/**
	 * @inheritdoc
	 */
	protected function renderItem($item) {
		if (isset($item['url'])) {
			$template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);

			return strtr($template, [
				'{id}'          => $item['id'],
				'{url}'         => Html::encode(Url::to($item['url'])),
				'{label}'       => $item['label'],
				'{frontendUrl}' => Url::to(ArrayHelper::getValue($item, 'frontendUrl')),
				'{urlCreate}'   => Url::to(ArrayHelper::getValue($item, 'urlCreate', '#')),
				'{urlDelete}'   => Url::to(ArrayHelper::getValue($item, 'urlDelete', '#')),
			]);
		}

		$template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);

		return strtr($template, [
			'{id}'          => $item['id'],
			'{label}'       => $item['label'],
			'{frontendUrl}' => Url::to(ArrayHelper::getValue($item, 'frontendUrl')),
			'{urlCreate}'   => Url::to(ArrayHelper::getValue($item, 'urlCreate', '#')),
			'{urlDelete}'   => Url::to(ArrayHelper::getValue($item, 'urlDelete', '#')),
		]);
	}

	/**
	 * @inheritdoc
	 */
	protected function isItemActive($item) {
		if (isset($item['url']) && is_array($item['url'])) {
			$id = ArrayHelper::getValue($item['url'], 'id');

			$requestId = \Yii::$app->request->get('id', \Yii::$app->request->get('parentId'));

			if ($requestId == $id) {
				return true;
			}
		}

		return false;
	}
}
