<?php

/**
 * @var yii\web\View $this
 * @var string $content
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Robots.txt Manager';
?>
<div>
	<?= Html::beginForm() ?>

	<div class="form-group">
		<?= Html::textarea('content', $content, [
			'class' => 'form-control',
			'rows'  => 25,
		]) ?>
	</div>

	<div class="form-group">
		<?= Html::submitButton('Сохранить', [
			'class' => 'btn btn-primary',
		]) ?>
	</div>

	<?= Html::endForm() ?>
</div>
