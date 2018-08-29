<?php

/**
 * @var yii\web\View $this
 * @var string $content
 * @var string[] $files
 * @var integer $currentFileIndex
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = 'Robots.txt Manager';

$this->registerJs(<<<JS
    (function() {
        var input = $('#robots-txt-selection-file');
        
        input.change(function(e) {
            input.closest('form').submit();
        });
    })();
JS
);
?>
<div>
	<?= Html::beginForm(['index'], 'get') ?>
    <div class="form-group">
		<?= Html::dropDownList('fileIndex', $currentFileIndex, ArrayHelper::getColumn($files, 'name'), [
			'id'    => 'robots-txt-selection-file',
			'class' => 'form-control',
		]) ?>
    </div>
	<?= Html::endForm() ?>

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
