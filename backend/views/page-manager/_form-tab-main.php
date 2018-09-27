<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \common\models\Page $model
 * @var \yii\widgets\ActiveForm $form
 * @var \common\models\forms\ImageUploadForm $imageUploadForm
 */

$imageManager = Yii::$app->imageManager;
?>
<div id="tab-panel-main" class="tab-pane fade in active">
	<?= $form->field($model, 'name')
		->textInput([
			'maxlength' => true,
		])
	?>

    <div>
        <div style="position: relative;">
			<?php if ($model->image_cover_id): ?>
				<?= Html::a(Html::img($imageManager->getThumbnailUrl($model->imageCover), [
					'class' => 'img-thumbnail',
				]), $imageManager->getOriginalUrl($model->imageCover), [
					'target' => '_blank',
				]) ?>

				<?= Html::a('<i class="glyphicon glyphicon-trash" aria-hidden="true"></i>', ['page-cover-delete', 'id' => $model->id], [
					'title'        => 'Удалить изображение',
					'data-pjax'    => 0,
					'data-confirm' => "Вы точно хотите удалить изображение?",
					'style'        => 'position: absolute; top: 10px; left: 10px;',
				]) ?>
			<?php endif; ?>
        </div>
		<?= $form->field($imageUploadForm, 'file')->fileInput([]) ?>
    </div>

	<?= \MrAnger\Yii2_HtmlEditorWidget\HtmlEditor::widget([
		'form'             => $form,
		'model'            => $model,
		'attribute'        => 'intro',
		'nameAttribute'    => 'selectedEditorIntro',
		'defaultEditor'    => $model->getParam('selectedEditorIntro', \MrAnger\Yii2_HtmlEditorWidget\HtmlEditor::WYSIWYG_EDITOR),
		'ckEditorOptions'  => [
			'preset' => 'minimal',
		],
		'aceEditorOptions' => [
			'mode'             => 'php',
			'theme'            => 'chrome',
			'containerOptions' => [
				'style' => 'width: 100%; min-height: 250px;',
			],
		],
	]) ?>

	<?= \MrAnger\Yii2_HtmlEditorWidget\HtmlEditor::widget([
		'form'          => $form,
		'model'         => $model,
		'attribute'     => 'content',
		'nameAttribute' => 'selectedEditorContent',
		'defaultEditor' => $model->getParam('selectedEditorContent', \MrAnger\Yii2_HtmlEditorWidget\HtmlEditor::WYSIWYG_EDITOR),
	]) ?>
</div>