<?php

/**
 * @var \yii\web\View $this
 * @var string $content
 */

use backend\assets\BackendAsset;
use yii\helpers\Html;

BackendAsset::register($this);
?>
<?php $this->beginContent('@app/views/layouts/plain.php') ?>
<div class="wrap">
	<div class="container" style="margin-top: 150px;">
		<?= \common\widgets\Alert::widget() ?>

		<?= $content ?>
	</div>
</div>
<?php $this->endContent() ?>
