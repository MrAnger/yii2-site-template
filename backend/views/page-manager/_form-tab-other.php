<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \common\models\Page $model
 * @var \yii\widgets\ActiveForm $form
 * @var array $layoutList
 * @var array $templateList
 */

$imageManager = Yii::$app->imageManager;

$helpListAsString = function ($paramList) {
	$html = "";

	foreach ($paramList as $paramData) {
		$html .= "<div><b>$paramData[0]</b> - $paramData[1]</div>";
	}

	return $html;
};
?>
<div id="tab-panel-other" class="tab-pane fade">
	<?= $form->field($model, 'is_enabled')->checkbox() ?>

	<?= $form->field($model, 'published_at')->widget(\kartik\datecontrol\DateControl::className(), [
		'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
	]) ?>

    <div class="row">
        <div class="col-md-6">
			<?= $form->field($model, 'layout')
				->dropDownList([null => 'По умолчанию'] + $layoutList)
			?>
        </div>
        <div class="col-md-6">
			<?= $form->field($model, 'file_template')
				->dropDownList([null => 'По умолчанию'] + $templateList)
			?>
        </div>
    </div>

	<?= $form->field($model, 'params')
		->hint("<pre>" . $helpListAsString(\common\models\Page::getHelpParams()) . "</pre>")
		->textarea([
			'rows'  => 7,
			'value' => $model->paramsAsString,
		]) ?>
</div>