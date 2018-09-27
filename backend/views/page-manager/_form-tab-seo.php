<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \common\models\Page $model
 * @var \yii\widgets\ActiveForm $form
 */

$imageManager = Yii::$app->imageManager;
?>
<div id="tab-panel-seo" class="tab-pane fade">
	<?= $form->field($model, 'slug')
		->textInput([
			'maxlength' => true,
		])
	?>

	<?= $form->field($model, 'meta_title')
		->textInput([
			'maxlength' => true,
		])
	?>

	<?= $form->field($model, 'meta_description')
		->textInput([
			'maxlength' => true,
		])
	?>

	<?= $form->field($model, 'meta_keywords')
		->textInput([
			'maxlength' => true,
		])
	?>
</div>