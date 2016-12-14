<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var User $model
 */

$this->title = Yii::t('app.actions', 'Editing');

$this->params['breadcrumbs'] = [
	['label' => Yii::t('app', 'Users Roles'), 'url' => ['index']],
	['label' => $model->displayName],
	Yii::t('app.actions', 'Edit'),
];

?>
<div class="model-update">
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>
</div>
