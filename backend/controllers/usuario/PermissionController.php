<?php

namespace backend\controllers\usuario;

use common\Rbac;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * @author MrAnger
 */
class PermissionController extends \Da\User\Controller\PermissionController {
	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow'       => true,
						'permissions' => [Rbac::PERMISSION_RBAC_MANAGER_ACCESS],
					],
				],
			],
		];
	}
}
