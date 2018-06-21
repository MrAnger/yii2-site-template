<?php

/**
 * @var yii\web\View $this
 * @var \common\models\Block $block
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = $block->name;

$this->params['breadcrumbs'] = [
	['label' => 'Блоки', 'url' => ['index']],
	$block->name,
];

?>
<?= $this->render('_form', [
	'block' => $block,
]) ?>