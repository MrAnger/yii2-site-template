<?php

/**
 * @var yii\web\View $this
 * @var \common\models\Page $page
 */

$pageContent = Yii::$app->shortCodeManager->parse($page->content);

/** @var \common\models\Block $beforeContentBlock */
$beforeContentBlock = null;
if ($page->getParam('before_content_block')) {
	$beforeContentBlock = \common\models\Block::findOne(['code' => $page->getParam('before_content_block')]);
}

/** @var \common\models\Block $afterContentBlock */
$afterContentBlock = null;
if ($page->getParam('after_content_block')) {
	$afterContentBlock = \common\models\Block::findOne(['code' => $page->getParam('after_content_block')]);
}
?>
<div>
	<?php if($beforeContentBlock && $beforeContentBlock->content): ?>
		<?= Yii::$app->shortCodeManager->parse($beforeContentBlock->content) ?>
	<?php endif; ?>

	<?= $pageContent ?>

	<?php if($afterContentBlock && $afterContentBlock->content): ?>
		<?= Yii::$app->shortCodeManager->parse($afterContentBlock->content) ?>
	<?php endif; ?>
</div>
