<?php

use common\models\User;
use common\Rbac;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var \yii\web\View $this
 * @var User $model
 */

/** @var \common\components\UserBuddy $userBuddy */
$userBuddy = Yii::$app->userBuddy;

$roleList = $userBuddy->getRoleDropdownList();

?>
<div>
	<?php $form = ActiveForm::begin([
		'enableClientValidation' => false,
	]) ?>

	<div class="row">
		<div class="col-md-6">
			<?= $form->field($model, 'roles')
				->checkboxList($roleList, [
					'item' => function ($index, $label, $name, $checked, $value) {
						$options = [
							'value' => $value,
							'label' => $label,
						];

						if ($value == Rbac::ROLE_MASTER) {
							$options['disabled'] = ' disabled';
						}

						$html = Html::checkbox($name, $checked, $options);

						return "<div class='checkbox'>$html</div>";
					},
				]) ?>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<?= $form->errorSummary($model) ?>

			<?= Html::submitButton(Yii::t('app.actions', 'Submit'), ['class' => 'btn btn-primary']) ?>
		</div>
	</div>

	<?php ActiveForm::end() ?>
</div>
