<?php

/**
 * @var yii\web\View $this
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = 'Настройки';

$this->params['breadcrumbs'] = [
	$this->title,
];
?>
<div>
	<?= Html::a('Сбросить кеш', ['cache-reset'], [
		'class' => 'btn btn-primary btn-xs',
	]) ?>
</div>
