<?php

/**
 * @var yii\web\View $this
 * @var \common\models\RedirectEntry $model
 */

use trntv\aceeditor\AceEditor;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

?>
<div>
	<?php $form = ActiveForm::begin([
		'enableClientValidation' => false,
	]) ?>

    <div class="row">
        <div class="col-md-6">
			<?= $form->field($model, 'from')
				->textInput([
					'maxlength' => true,
				])
			?>
        </div>
        <div class="col-md-6">
			<?= $form->field($model, 'to')
				->textInput([
					'maxlength' => true,
				])
			?>
        </div>
    </div>

	<?= $form->field($model, 'code')->dropDownList([
		301 => "301 - страница постоянно доступна на указанном URL",
		302 => "302 - страница временно доступна на указанном URL",
	]) ?>

    <div class="form-group text-right">
		<?= Html::submitButton(($model->isNewRecord) ? Yii::t('app.actions', 'Create') : Yii::t('app.actions', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>

	<?php $form->end() ?>
</div>