<?php

namespace backend\controllers\usuario;

use common\Rbac;
use Yii;
use yii\web\ForbiddenHttpException;

/**
 * @author MrAnger
 */
class AdminController extends \Da\User\Controller\AdminController {
	public function actionUpdate($id) {
		if (!Yii::$app->user->can(Rbac::PERMISSION_USER_EDIT, ['userId' => $id]))
			throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));

		return parent::actionUpdate($id);
	}

	public function actionUpdateProfile($id) {
		if (!Yii::$app->user->can(Rbac::PERMISSION_USER_EDIT, ['userId' => $id]))
			throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));

		return parent::actionUpdateProfile($id);
	}

	public function actionAssignments($id) {
		if (!Yii::$app->user->can(Rbac::PERMISSION_USER_ROLE_CHANGE, ['userId' => $id]))
			throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));

		return parent::actionAssignments($id);
	}

	public function actionBlock($id) {
		if (!Yii::$app->user->can(Rbac::PERMISSION_USER_BLOCK, ['userId' => $id]))
			throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));

		return parent::actionBlock($id);
	}

	public function actionDelete($id) {
		if (!Yii::$app->user->can(Rbac::PERMISSION_USER_DELETE, ['userId' => $id]))
			throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));

		return parent::actionDelete($id);
	}

	public function actionSwitchIdentity($id = null) {
		if (!Yii::$app->user->can(Rbac::PERMISSION_SWITCH_IDENTITY_ACCESS, ['userId' => $id]))
			throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));

		return parent::actionSwitchIdentity($id);
	}
}
