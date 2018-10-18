<?php

/**
 * @var yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = 'Редиректы';

$this->params['breadcrumbs'] = [
	$this->title,
];
?>
<div>

    <p class="text-right">
		<?= Html::a(Yii::t('app.actions', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'tableOptions' => ['class' => 'table table-hover'],
		'columns'      => [
			'code',
			[
				'attribute' => 'from',
				'format'    => 'raw',
				'value'     => function (\common\models\RedirectEntry $model) {
					$url = Yii::$app->frontendUrlManager->createAbsoluteUrl($model->from);

					return Html::a($url, $url, [
						'target'    => '_blank',
						'data-pjax' => 0,
					]);
				},
			],
			[
				'attribute' => 'to',
				'format'    => 'raw',
				'value'     => function (\common\models\RedirectEntry $model) {
					$url = Yii::$app->frontendUrlManager->createAbsoluteUrl($model->to);

					return Html::a($url, $url, [
						'target'    => '_blank',
						'data-pjax' => 0,
					]);
				},
			],
			[
				'class'    => 'yii\grid\ActionColumn',
				'template' => '{update} {delete}',
			],
		],
	]) ?>
</div>