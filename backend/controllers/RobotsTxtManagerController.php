<?php

namespace backend\controllers;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * @author MrAnger
 */
class RobotsTxtManagerController extends BaseController {
	/**
	 * @var string[]
	 */
	protected $files;

	public function init() {
		parent::init();

		$this->files = ArrayHelper::getValue(Yii::$app->params, 'robotsTxtFiles', []);
	}

	public function actionIndex($file = null) {
		$request = Yii::$app->request;

		if (empty($this->files)) {
			throw new InvalidConfigException("Массив files пуст. Настройте список файлов для редактирования.");
		}

		if ($file === null) {
			$file = ArrayHelper::getValue($this->files, 0);
		}

		$currentFile = $file;
		$file = Yii::getAlias($file);

		if (!file_exists($file))
			file_put_contents($file, '');

		$content = file_get_contents($file);

		if ($request->isPost && $request->post('content') !== null) {
			$newContent = $request->post('content');

			$fp = fopen($file, "w+");
			ftruncate($fp, 0);
			fputs($fp, $newContent);
			fclose($fp);

			Yii::$app->session->addFlash('success', 'Файл успешно изменен.');

			return $this->refresh();
		}

		return $this->render('index', [
			'files'       => $this->files,
			'currentFile' => $currentFile,
			'content'     => $content,
		]);
	}
}
