<?php
namespace backend\controllers;

use common\Rbac;
use Yii;
use yii\web\ForbiddenHttpException;

/**
 * @author MrAnger
 */
class AdminController extends \dektrium\user\controllers\AdminController {
	public function actionUpdate($id) {
		if (!Yii::$app->user->can(Rbac::PERMISSION_USER_EDIT, ['userId' => $id]))
			throw new ForbiddenHttpException(Yii::t('app', 'Forbidden'));

		return parent::actionUpdate($id);
	}

	public function actionUpdateProfile($id) {
		if (!Yii::$app->user->can(Rbac::PERMISSION_USER_EDIT, ['userId' => $id]))
			throw new ForbiddenHttpException(Yii::t('app', 'Forbidden'));

		return parent::actionUpdateProfile($id);
	}

	public function actionDelete($id) {
		if (!Yii::$app->user->can(Rbac::PERMISSION_USER_DELETE, ['userId' => $id]))
			throw new ForbiddenHttpException(Yii::t('app', 'Forbidden'));

		return parent::actionDelete($id);
	}

	public function actionBlock($id) {
		if (!Yii::$app->user->can(Rbac::PERMISSION_USER_BLOCK, ['userId' => $id]))
			throw new ForbiddenHttpException(Yii::t('app', 'Forbidden'));

		return parent::actionBlock($id);
	}
}
