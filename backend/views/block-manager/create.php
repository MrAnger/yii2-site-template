<?php

/**
 * @var yii\web\View $this
 * @var \common\models\Block $block
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = 'Создать новый блок';

$this->params['breadcrumbs'] = [
	['label' => 'Блоки', 'url' => ['index']],
	'Создать',
];

?>
<?= $this->render('_form', [
	'block' => $block,
]) ?>