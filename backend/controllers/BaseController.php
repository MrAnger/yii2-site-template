<?php

namespace backend\controllers;

use common\Rbac;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * @author MrAnger
 */
abstract class BaseController extends Controller {
	/**
	 * @return array
	 */
	public function behaviors() {
		return [
			'accessControl' => [
				'class' => AccessControl::className(),
				'rules' => $this->getAccessRules(),
			],
			'verbFilter'    => [
				'class'   => VerbFilter::className(),
				'actions' => $this->getVerbs(),
			],
		];
	}

	/**
	 * Возвращает массив правил доступа. Переопределив данный метод можно дополнить или полностью заменить правила.
	 *
	 * @return array
	 */
	public function getAccessRules() {
		return [
			[
				'allow'       => true,
				'permissions' => [Rbac::PERMISSION_CONTROL_PANEL_ACCESS],
			],
		];
	}

	/**
	 * @return array
	 */
	public function getVerbs() {
		return [
			'delete' => ['POST'],
		];
	}
}