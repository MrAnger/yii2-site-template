<?php

/**
 * @var yii\web\View $this
 * @var \common\models\Page $model
 * @var \common\models\forms\ImageUploadForm $imageUploadForm
 * @var array $menu
 * @var array $layoutList
 * @var array $templateList
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = 'Изменить страницу';

$this->params['breadcrumbs'] = [
	['label' => 'Страницы', 'url' => ['update']],
	['label' => $model->name, 'url' => ['update', 'id' => $model->id]],
	'Изменить',
];
?>
<div class="row">
    <div class="col-md-3">
		<?= $this->render('_pages-menu', [
			'menu' => $menu,
		]) ?>
    </div>

    <div class="col-md-9">
		<?= $this->render('_form', [
			'model'           => $model,
			'imageUploadForm' => $imageUploadForm,
			'layoutList'      => $layoutList,
			'templateList'    => $templateList,
		]) ?>
    </div>
</div>