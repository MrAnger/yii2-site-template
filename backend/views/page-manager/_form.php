<?php

/**
 * @var yii\web\View $this
 * @var \common\models\Page $model
 * @var \common\models\forms\ImageUploadForm $imageUploadForm
 * @var \yii\data\ActiveDataProvider $galleryImageDataProvider
 * @var array $layoutList
 * @var array $templateList
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
		<?php if (!$model->isNewRecord): ?>
            <li><a data-toggle="tab" href="#tab-panel-images-gallery">Галерея изображений</a></li>
		<?php endif; ?>
        <li><a data-toggle="tab" href="#tab-panel-other">Другие настройки</a></li>
    </ul>

    <div class="tab-content">
		<?= $this->render('_form-tab-main', [
			'model'           => $model,
			'form'            => $form,
			'imageUploadForm' => $imageUploadForm,
		]) ?>

		<?= $this->render('_form-tab-seo', [
			'model' => $model,
			'form'  => $form,
		]) ?>

		<?php if (!$model->isNewRecord): ?>
			<?= $this->render('_form-tab-gallery-image', [
				'model'                    => $model,
				'galleryImageDataProvider' => $galleryImageDataProvider,
			]) ?>
		<?php endif; ?>

		<?= $this->render('_form-tab-other', [
			'model'        => $model,
			'form'         => $form,
			'layoutList'   => $layoutList,
			'templateList' => $templateList,
		]) ?>
    </div>

    <div style="margin-top: 5px;">
		<?= Html::submitButton(($model->isNewRecord) ? Yii::t('app.actions', 'Create') : Yii::t('app.actions', 'Save'), ['class' => 'btn btn-primary btn-block']) ?>
    </div>

	<?php $form->end() ?>

	<?php if (!$model->isNewRecord): ?>
		<?= $this->render('_modal-gallery-image-update') ?>
	<?php endif; ?>
</div>