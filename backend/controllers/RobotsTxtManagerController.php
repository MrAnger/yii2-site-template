<?php

namespace backend\controllers;

use Yii;

/**
 * @author MrAnger
 */
class RobotsTxtManagerController extends BaseController {
	public $file = '@frontend/web/robots.txt';

	public function init() {
		parent::init();

		$this->file = Yii::getAlias($this->file);
	}

	public function actionIndex() {
		$request = Yii::$app->request;

		if (!file_exists($this->file))
			file_put_contents($this->file, '');

		$content = file_get_contents($this->file);

		if ($request->isPost && $request->post('content') !== null) {
			$newContent = $request->post('content');

			$fp = fopen($this->file, "w+");
			ftruncate($fp, 0);
			fputs($fp, $newContent);
			fclose($fp);

			Yii::$app->session->addFlash('success', 'Файл успешно изменен.');

			return $this->redirect(['index']);
		}

		return $this->render('index', [
			'content' => $content,
		]);
	}
}
