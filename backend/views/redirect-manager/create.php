<?php

/**
 * @var yii\web\View $this
 * @var \common\models\RedirectEntry $model
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = 'Создать новый редирект';

$this->params['breadcrumbs'] = [
	['label' => 'Редиректы', 'url' => ['index']],
	'Создать',
];

?>
<?= $this->render('_form', [
	'model' => $model,
]) ?>