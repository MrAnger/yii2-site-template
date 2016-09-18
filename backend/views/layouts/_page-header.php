<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 */

$user = Yii::$app->user;

?>
<div class="row">
	<div class="col-md-12">
		<?php if (isset($this->params['breadcrumbs'])): ?>
			<?= \yii\widgets\Breadcrumbs::widget([
				'links' => $this->params['breadcrumbs'],
			]) ?>
		<?php else: ?>
			<ul class="breadcrumb">
				<li>
					<?= Html::a(Yii::t('yii', 'Home'), Url::to(Yii::$app->homeUrl)) ?>
				</li>
			</ul>
		<?php endif ?>
		<h4><?= $this->title ?></h4>
	</div>
</div>