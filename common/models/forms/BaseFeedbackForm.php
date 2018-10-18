<?php

namespace common\models\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * @property  boolean $isUseCaptcha
 * @property  boolean $isUseAcceptPrivacy
 */
abstract class BaseFeedbackForm extends Model {
	const SCENARIO_VALIDATE = 'scenario-validate';
	const SCENARIO_CREATE = 'scenario-create';

	/**
	 * @var array
	 * Карта типов форм. тип => имя класса формы
	 */
	public static $typeMap = [
		1 => 'common\models\forms\CallbackForm',
		2 => 'common\models\forms\OrderForm',
		3 => 'common\models\forms\PartnershipForm',
	];

	/**
	 * @var string
	 * В этом поле можно хранить описание формы, выводимой на публичной части.
	 * Полезно, когда выводится одна и та же форма, но в разных местах сайта и необходимо понимать из какой именно
	 *     формы был отправлен запрос.
	 */
	public $formDescription;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $phone;

	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var string
	 */
	public $message;

	/**
	 * @var string
	 */
	public $captcha;

	/**
	 * @var boolean
	 */
	public $acceptPrivacy;

	/**
	 * @var UploadedFile|UploadedFile[]
	 */
	public $file;

	/**
	 * @var integer
	 * Идентификатор модели формы. Служит для идентифицирования модели.
	 */
	protected $type;

	/**
	 * @var boolean
	 * Использовать ли капчу
	 */
	protected $useCaptcha = false;

	/**
	 * @var boolean
	 * Использовать ли согласие с политикой конфидициальности
	 */
	protected $useAcceptPrivacy = false;

	/**
	 * @var array
	 * Здесь хранится список email адресов, на которые нужно отправить уведомление о поступившем запросе
	 */
	protected $notificationEmailList = [];

	/**
	 * @var string
	 * Путь к папке, в которой будут лежать загруженные файлы
	 */
	protected $uploadFilesDir = "@frontend/web/feedback/uploads";

	/**
	 * @var string
	 * Ссылка до загруженных файлов
	 */
	protected $uploadFilesUrlPath = "feedback/uploads";

	/**
	 * @inheritdoc
	 */
	public function init() {
		parent::init();

		$this->notificationEmailList = ArrayHelper::getValue(Yii::$app->params, 'feedbackNotificationEmailList', []);

		$this->uploadFilesDir = Yii::getAlias($this->uploadFilesDir);
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios() {
		$defaultScenario = ArrayHelper::getValue(parent::scenarios(), self::SCENARIO_DEFAULT, []);

		return array_merge(parent::scenarios(), [
			self::SCENARIO_VALIDATE => $defaultScenario,
			self::SCENARIO_CREATE   => $defaultScenario,
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		$rules = [
			[['formDescription', 'name', 'phone', 'email', 'message'], 'string'],

			[['formDescription', 'name', 'phone', 'email', 'message'], 'trim'],
			[['formDescription', 'name', 'phone', 'email', 'message'], 'default'],

			[['phone'], 'match', 'pattern' => '/^\+7 \d{3} \d{3}\-\d{2}\-\d{2}$/i'],
			[['email'], 'email'],

			[['file'], 'file', 'maxFiles' => ini_get('max-file-uploads'), 'extensions' => [
				'jpg', 'jpeg', 'png', 'bmp', 'gif',
				'txt', 'rtf', 'doc', 'docx', 'pdf',
				'zip',
			]],
		];

		if ($this->useCaptcha) {
			$rules[] = [['captcha'], 'required'];
			$rules[] = [['captcha'], 'string'];
			$rules[] = [['captcha'], 'trim'];
			$rules[] = [['captcha'], 'default'];
			$rules[] = [['captcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'on' => [self::SCENARIO_CREATE]];
		}

		if ($this->useAcceptPrivacy) {
			$rules[] = [['acceptPrivacy'], 'required'];
			$rules[] = [['acceptPrivacy'], 'boolean'];
			$rules[] = [['acceptPrivacy'], 'compare', 'compareValue' => 1, 'operator' => '==', 'message' => 'Вы должны согласиться с политикой конфиденциальности.'];
		}

		return $rules;
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'formDescription' => 'Описание формы обратной связи',

			'name'    => 'Имя',
			'phone'   => 'Телефон',
			'email'   => 'Email',
			'message' => 'Сообщение',
			'file'    => 'Файл',

			'captcha'       => 'Защитный код',
			'acceptPrivacy' => 'Согласие с политикой конфиденциальности',
		];
	}

	public function afterValidate() {
		$result = parent::afterValidate();

		if ($this->hasErrors('captcha')) {
			$this->clearErrors('captcha');
			$this->addError('captcha', 'Пожалуйста, подтвердите, что Вы не робот :)');
		}

		return $result;
	}

	/**
	 * @return integer
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return array
	 */
	public function getNotificationEmailList() {
		return $this->notificationEmailList;
	}

	/**
	 * @return array
	 * Возвращает список названий атрибутов, которые необходимо сохранить.
	 */
	public function formAttributes() {
		return [
			'formDescription',
			'name',
			'phone',
			'email',
			'message',
			'file',
		];
	}

	/**
	 * @return boolean
	 */
	public function getIsUseCaptcha() {
		return $this->useCaptcha;
	}

	/**
	 * @return boolean
	 */
	public function getIsUseAcceptPrivacy() {
		return $this->useAcceptPrivacy;
	}

	/**
	 * @param $type
	 *
	 * @return self|null
	 *
	 * @throws
	 */
	public static function getFormByType($type) {
		$map = [];

		$className = ArrayHelper::getValue(self::$typeMap, $type);

		if ($className === null)
			return null;

		return Yii::createObject([
			'class' => $className,
		]);
	}

	/**
	 * @return array
	 *
	 * @throws
	 */
	public function getFormData() {
		$output = [];

		foreach ($this->formAttributes() as $attributeName) {
			$attributeValue = $this->{$attributeName};

			if (empty($attributeValue)) {
				continue;
			}

			$item = [
				'type'      => ($attributeName == 'files') ? 'file' : 'text',
				'label'     => $this->getAttributeLabel($attributeName),
				'attribute' => $attributeName,
				'value'     => $attributeValue,
			];

			if ($attributeName == 'file') {
				FileHelper::createDirectory($this->uploadFilesDir);

				if (is_array($this->file)) {
					$item['value'] = [];

					foreach ($this->file as $file) {
						$fileName = $file->name;
						$this->generateUniqueFileName($this->uploadFilesDir, $fileName);

						if (!$file->saveAs("$this->uploadFilesDir/$fileName")) {
							$originalFileName = $file->name;

							throw new \Exception("Не удалось сохранить файл $originalFileName.");
						}

						$item['value'][] = Url::to("$this->uploadFilesUrlPath/$fileName", true);
					}
				} else {
					$fileName = $this->file->name;
					$this->generateUniqueFileName($this->uploadFilesDir, $fileName);

					if (!$this->file->saveAs("$this->uploadFilesDir/$fileName")) {
						$originalFileName = $this->file->name;

						throw new \Exception("Не удалось сохранить файл $originalFileName.");
					}

					$item['value'] = Url::to("$this->uploadFilesUrlPath/$fileName", true);
				}
			}

			$output[] = $item;
		}

		return $output;
	}

	/**
	 * @param array $data
	 *
	 * @return string
	 */
	public static function getFormDataAsHtml($data) {
		$output = "";

		foreach ($data as $item) {
			$label = ArrayHelper::getValue($item, 'label');
			$value = ArrayHelper::getValue($item, 'value');
			$type = ArrayHelper::getValue($item, 'type');

			$valueHtml = $value;

			if ($type == 'file') {
				if (is_array($value)) {
					$valueHtml = Html::ul(array_walk($value, function (&$item) {
						$item = Html::a($item, $item, [
							'target'    => '_blank',
							'data-pjax' => 0,
						]);
					}));
				} else {
					$valueHtml = Html::a($value, $value, [
						'target'    => '_blank',
						'data-pjax' => 0,
					]);
				}
			} else {
				if (is_array($value)) {
					$valueHtml = Html::ul($value);
				}
			}

			$output .= "<p><b>$label:</b> $valueHtml</p>";
		}

		return $output;
	}

	/**
	 * @param string $dir
	 * @param string $fileName
	 *
	 * @return string
	 */
	protected function generateUniqueFileName($dir, &$fileName) {
		$ext = ArrayHelper::getValue(pathinfo($fileName), 'extension');
		$baseName = basename($fileName, ($ext) ? ".$ext" : null);

		$baseName = Inflector::slug($baseName);
		$fileName = $baseName . (($ext) ? ".$ext" : "");

		$index = 2;
		while (file_exists("$dir/$fileName")) {
			$fileName = $baseName . "_$index" . (($ext) ? ".$ext" : "");

			$index++;
		}

		return $fileName;
	}
}
