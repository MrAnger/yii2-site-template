<?php

namespace MrAnger\Yii2_HtmlEditorWidget;

use mranger\ckeditor\CKEditor;
use trntv\aceeditor\AceEditor;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

class HtmlEditor extends Widget {
	const HTML_EDITOR = 'html-editor';
	const WYSIWYG_EDITOR = 'wysiwyg-editor';

	public $editorLabels = [
		self::HTML_EDITOR    => 'Html',
		self::WYSIWYG_EDITOR => 'Визуальный редактор',
	];

	/**
	 * @var string
	 */
	public $defaultEditor = self::HTML_EDITOR;

	/**
	 * @var ActiveForm
	 */
	public $form;

	/**
	 * @var Model
	 */
	public $model;

	/**
	 * @var string
	 */
	public $attribute;

	/**
	 * @var array
	 */
	public $options = [];

	/**
	 * @var array
	 */
	public $aceEditorOptions = [
		'mode'             => 'php',
		'theme'            => 'chrome',
		'containerOptions' => [
			'style' => 'width: 100%; min-height: 550px;',
		],
	];

	/**
	 * @var array
	 */
	public $ckEditorOptions = [];

	/**
	 * @var array
	 */
	public $buttonsOptions = [
		'class'       => 'btn-group',
		'data-toggle' => 'buttons',
		'style'       => 'margin-bottom: 0 !important;',

		'inputOptions' => [
			'class' => 'form-check-input',
		],

		'labelOptions' => [
			'class' => 'form-check-label btn btn-default btn-rounded btn-xs',
			'style' => 'outline: none;',
		],
	];

	/**
	 * @var callable
	 */
	public $aceEditorInitFunction;

	/**
	 * @var callable
	 */
	public $ckEditorInitFunction;

	/**
	 * @var string
	 */
	public $layout = "<div class='text-right'>{buttons}</div><div class='source'>{editors}</div>";

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();

		if ($this->model === null) {
			throw new InvalidConfigException('Please specify the "model" property.');
		}

		if (!$this->model instanceof Model) {
			throw new InvalidConfigException('The "model" property must be either a Model object.');
		}

		if ($this->attribute === null) {
			throw new InvalidConfigException('Please specify the "attribute" property.');
		}

		if (!isset($this->options['id'])) {
			$this->options['id'] = $this->getId();
		}

		if ($this->form === null) {
			throw new InvalidConfigException('Please specify the "form" property.');
		}

		if (!$this->form instanceof ActiveForm) {
			throw new InvalidConfigException('The "model" property must be either a ActiveForm object.');
		}

		if ($this->aceEditorInitFunction === null) {
			$this->aceEditorInitFunction = function ($widget) {
				/** @var static $widget */

				return $widget->form->field($widget->model, $widget->attribute, ['options' => ['class' => 'html-editor-wrapper']])
					->widget(AceEditor::className(), array_merge([
						'options' => [
							'id' => $this->getHtmlEditorId(),
						],
					], $this->aceEditorOptions));
			};
		}

		if ($this->ckEditorInitFunction === null) {
			$this->ckEditorInitFunction = function ($widget) {
				/** @var static $widget */

				return $widget->form->field($widget->model, $widget->attribute, ['options' => ['class' => 'wysiwyg-editor-wrapper']])
					->widget(CKEditor::className(), array_merge([
						'options' => [
							'id' => $this->getWysiwygEditorId(),
						],
					], $this->ckEditorOptions));
			};
		}
	}

	/**
	 * @inheritdoc
	 */
	public function run() {
		HtmlEditorAsset::register($this->view);

		$data = [
			'editorList' => [
				'html-editor'    => [
					'wrapper' => '.html-editor-wrapper',
					'input'   => '#' . $this->getHtmlEditorId(),
				],
				'wysiwyg-editor' => [
					'wrapper' => '.wysiwyg-editor-wrapper',
					'input'   => '#' . $this->getWysiwygEditorId(),
				],
			],
		];

		$this->view->registerJs("htmlEditorInit('#$this->id', " . Json::encode($data) . ");"); // Важно что бы инициализация приложения прошла раньше, чем инициализация редакторов

		$html = Html::tag('div', str_replace([
			'{buttons}',
			'{editors}',
		], [
			$this->renderButtons(),
			$this->renderEditors(),
		], $this->layout), $this->options);

		return $html;
	}

	/**
	 * @return string
	 */
	public function getHtmlEditorId() {
		return $this->id . "_html-editor";
	}

	/**
	 * @return string
	 */
	public function getWysiwygEditorId() {
		return $this->id . "_wysiwyg-editor";
	}

	/**
	 * @return string
	 */
	public function renderEditors() {
		$aceEditor = $this->aceEditorInitFunction;
		$ckEditor = $this->ckEditorInitFunction;

		return implode("\n", [
			$aceEditor($this),
			$ckEditor($this),
		]);
	}

	/**
	 * @return string
	 */
	public function renderButtons() {
		$options = $this->buttonsOptions;

		$inputOptions = ArrayHelper::getValue($options, 'inputOptions', []);
		unset($options['inputOptions']);

		$labelOptions = ArrayHelper::getValue($options, 'labelOptions', []);
		unset($options['labelOptions']);

		return Html::tag('div', Html::radioList('source-view-' . $this->id, $this->defaultEditor, $this->editorLabels, [
			'tag'  => false,
			'item' => function ($index, $label, $name, $checked, $value) use ($inputOptions, $labelOptions) {
				$_labelOptions = $labelOptions;

				if ($checked) {
					if (!array_key_exists('class', $_labelOptions)) {
						$_labelOptions['class'] = 'active';
					} else {
						$_labelOptions['class'] .= ' active';
					}
				}

				return Html::label(Html::radio($name, $checked, array_merge(['value' => $value], $inputOptions)) . $label, null, array_merge(
					[], $_labelOptions
				));
			},
		]), $options);
	}
}
