<?php

/**
 * @var yii\web\View $this
 * @var \common\models\Page $page
 */

?>
<div>
	<?= Yii::$app->shortCodeManager->parse($page->content) ?>
</div>
