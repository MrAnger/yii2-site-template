<?php

/**
 * @var \yii\web\View $this
 * @var string $content
 */

use frontend\assets\FrontendAsset;
use yii\helpers\Html;

FrontendAsset::register($this);
?>
<?php $this->beginContent('@app/views/layouts/plain.php') ?>
<div class="wrap">
	<div class="container" style="margin-top: 150px;">
		<?= $content ?>
	</div>
</div>
<?php $this->endContent() ?>
