<?php

/**
 * @var \yii\web\View $this
 */

use yii\helpers\Html;
use frontend\assets\FrontendAsset;

$modalList = [
	'//modal-callback',
	'//modal-thank',
];
?>
<?php foreach ($modalList as $path): ?>
	<?= $this->render($path) ?>
<?php endforeach; ?>
