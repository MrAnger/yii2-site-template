<?php
namespace backend\controllers;

use common\Rbac;
use Yii;

/**
 * @author MrAnger
 */
class SiteController extends BaseController {
	public function getAccessRules() {
		return [
			[
				'allow'   => true,
				'roles'   => ['@', '?'],
				'actions' => ['error'],
			],
			[
				'allow' => true,
				'roles' => Rbac::getRoleList(),
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'error'                => [
				'class' => 'yii\web\ErrorAction',
			],
			'ckeditor-file-upload' => [
				'class' => 'mranger\ckeditor\actions\FileUploadAction',
			],
		];
	}

	public function actionIndex() {
		return $this->render('index');
	}

	public function beforeAction($action) {
		$result = parent::beforeAction($action);

		if ($action->id == 'error' && Yii::$app->user->isGuest)
			$this->layout = 'plain';

		return $result;
	}
}
