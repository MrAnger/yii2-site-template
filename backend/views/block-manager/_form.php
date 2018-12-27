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

$helpListAsString = function ($paramList) {
	$html = "";

	foreach ($paramList as $paramData) {
		$html .= "<div><b>$paramData[0]</b> - $paramData[1]</div>";
	}

	return $html;
};

$shortCodesHelpString = $helpListAsString(\common\models\Page::getHelpShortCodeList());
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
		'form'          => $form,
		'model'         => $block,
		'attribute'     => 'content',
		'nameAttribute' => 'selectedEditorContent',
		'defaultEditor' => $block->getParam('selectedEditorContent', \MrAnger\Yii2_HtmlEditorWidget\HtmlEditor::HTML_EDITOR),
		'layout'        => <<<HTML
<div class='text-right'>{buttons}</div>
<div class='source'>{editors}</div>
<div class="hint-block">
    <pre>$shortCodesHelpString</pre>
</div>
HTML
		,
	]) ?>

	<?= $form->field($block, 'params')->textarea([
		'rows'  => 7,
		'value' => $block->paramsAsString,
	]) ?>

    <div class="form-group text-right">
		<?= Html::submitButton(($block->isNewRecord) ? Yii::t('app.actions', 'Create') : Yii::t('app.actions', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>

	<?php $form->end() ?>
</div>