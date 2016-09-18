<?php

/**
 * @var $this \yii\web\View
 * @var $content string
 */

use yii\helpers\Html;
use frontend\assets\FrontendAsset;

FrontendAsset::register($this);
?>
<?php $this->beginContent('@app/views/layouts/plain.php') ?>
	<div class="wrap">
		<div class="container">
			<?= $content ?>
		</div>
	</div>
<?php $this->endContent() ?>