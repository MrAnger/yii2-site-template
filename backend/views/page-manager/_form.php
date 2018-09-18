<?php

/**
 * @var yii\web\View $this
 * @var \common\models\Page $model
 * @var \common\models\forms\ImageUploadForm $imageUploadForm
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$imageManager = Yii::$app->imageManager;
?>
<div>
	<?php $form = ActiveForm::begin([
		'enableClientValidation' => false,
	]) ?>

	<?= $form->errorSummary($model) ?>

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab-panel-main">Страница</a></li>
        <li><a data-toggle="tab" href="#tab-panel-seo">SEO</a></li>
        <li><a data-toggle="tab" href="#tab-panel-other">Другие настройки</a></li>
    </ul>

    <div class="tab-content">
        <div id="tab-panel-main" class="tab-pane fade in active">
			<?= $form->field($model, 'name')
				->textInput([
					'maxlength' => true,
				])
			?>

            <div>
                <div>
					<?php if ($model->image_cover_id): ?>
						<?= Html::a(Html::img($imageManager->getThumbnailUrl($model->imageCover), [
							'class' => 'img-thumbnail',
						]), $imageManager->getOriginalUrl($model->imageCover), [
							'target' => '_blank',
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

        <div id="tab-panel-other" class="tab-pane fade">
			<?= $form->field($model, 'is_enabled')->checkbox() ?>

			<?= $form->field($model, 'published_at')->widget(\kartik\datecontrol\DateControl::className(), [
				'type' => \kartik\datecontrol\DateControl::FORMAT_DATETIME,
			]) ?>

            <div class="row">
                <div class="col-md-6">
					<?= $form->field($model, 'layout')
						->textInput([
							'maxlength' => true,
						])
					?>
                </div>
                <div class="col-md-6">
					<?= $form->field($model, 'file_template')
						->textInput([
							'maxlength' => true,
						])
					?>
                </div>
            </div>

			<?= $form->field($model, 'params')->textarea([
				'rows'  => 7,
				'value' => $model->paramsAsString,
			]) ?>
        </div>
    </div>

    <div style="margin-top: 5px;">
		<?= Html::submitButton(($model->isNewRecord) ? Yii::t('app.actions', 'Create') : Yii::t('app.actions', 'Save'), ['class' => 'btn btn-primary btn-block']) ?>
    </div>

	<?php $form->end() ?>
</div>