<?php

/**
 * @var \yii\web\View $this
 * @var string $content
 */

use yii\helpers\Html;

$titleBase = Yii::t('app', 'Control Panel');

$request = Yii::$app->request;

if (!empty($this->title))
	$title = $this->title . ' — ' . $titleBase;
else
	$title = $titleBase;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<base href="<?= $request->getHostInfo() . $request->getBaseUrl() ?>/">
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($title) ?></title>
	<?= $this->render('_head') ?>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
