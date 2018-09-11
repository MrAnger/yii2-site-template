<?php

/**
 * @var yii\web\View $this
 * @var \common\models\Block $block
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
			<?= $form->field($block, 'name')
				->textInput([
					'maxlength' => true,
				])
			?>
        </div>
        <div class="col-md-6">
			<?= $form->field($block, 'code')
				->textInput([
					'maxlength' => true,
				])
			?>
        </div>
    </div>

	<?= \MrAnger\Yii2_HtmlEditorWidget\HtmlEditor::widget([
		'form'      => $form,
		'model'     => $block,
		'attribute' => 'content',
	]) ?>

    <div class="form-group text-right">
		<?= Html::submitButton(($block->isNewRecord) ? Yii::t('app.actions', 'Create') : Yii::t('app.actions', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>

	<?php $form->end() ?>
</div>