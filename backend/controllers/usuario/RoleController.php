<?php

namespace backend\controllers\usuario;

use common\Rbac;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * @author MrAnger
 */
class RoleController extends \Da\User\Controller\RoleController {
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
