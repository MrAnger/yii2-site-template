<?php

namespace common\widgets;

use common\assets\DropzoneAsset;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

class Dropzone extends Widget {
	/**
	 * @var string
	 */
	public $attributeName = 'file';

	/**
	 * @var string
	 */
	public $uploadUrl;

	/**
	 * @var array
	 */
	public $dropzoneOptions = [
		'thumbnailWidth'   => 220,
		'thumbnailHeight'  => 220,
		'autoProcessQueue' => true,

		'dictDefaultMessage' => 'Выберите файлы для загрузки',
	];

	/**
	 * @var JsExpression
	 */
	public $onError;

	/**
	 * @var JsExpression
	 */
	public $onQueueComplete;

	/**
	 * @var array
	 */
	public $options = [
		'class' => 'dropzone',
	];

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();

		if (empty($this->uploadUrl)) {
			throw new InvalidConfigException("UploadUrl not setted.");
		}

		if (!isset($this->options['id'])) {
			$this->options['id'] = $this->id;
		}

		$this->dropzoneOptions = array_merge($this->dropzoneOptions, [
			'url' => Url::to($this->uploadUrl),
		]);

		$params = ArrayHelper::getValue($this->dropzoneOptions, 'params', []);
		$params[Yii::$app->request->csrfParam] = Yii::$app->request->csrfToken;
		$this->dropzoneOptions['params'] = $params;

		$customErrorHandler = null;
		if ($this->onError) {
			$customErrorHandler = $this->onError->expression;
		}

		$this->dropzoneOptions['error'] = new JsExpression("
			function(file, response, xhr){
				file.previewElement.classList.add(\"dz-error\");
				$(file.previewElement).find(\".dz-error-message span\").text(response.message);
				
				(function(file, response, xhr){
					$customErrorHandler
				})(file, response, xhr);
			}
		");
	}

	/**
	 * @return string
	 */
	public function run() {
		DropzoneAsset::register($this->view);

		$this->view->registerJs("Dropzone.autoDiscover = false;", View::POS_END);

		$id = $this->id;
		$dropzoneOptions = Json::encode($this->dropzoneOptions);

		$queueCompleteCallback = null;
		if ($this->onQueueComplete) {
			$queueCompleteCallback = $this->onQueueComplete->expression;
		}

		$this->view->registerJs(<<<JS
(function() {
    var dropzone = new Dropzone("#$id", $dropzoneOptions);
    
    dropzone.on("queuecomplete", $queueCompleteCallback);
})();
JS
		);

		return $this->generateDropzoneHtml();
	}

	/**
	 * @return string
	 */
	protected function generateDropzoneHtml() {
		return Html::tag('div',
			Html::tag('div',
				Html::fileInput($this->attributeName, null, [
					'multiple' => 'multiple',
				])
				, ['class' => 'fallback'])
			, $this->options);
	}
}
