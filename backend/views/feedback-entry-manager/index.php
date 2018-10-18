<?php

/**
 * @var yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = 'Обратная связь';

$this->params['breadcrumbs'] = [
	$this->title,
];

?>
<div>
	<?php \yii\widgets\Pjax::begin([
		'id'      => 'pjax-item-list',
		'timeout' => 8000,
	]) ?>

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'tableOptions' => ['class' => 'table table-hover'],
		'columns'      => [
			[
				'attribute' => 'created_at',
				'format'    => 'raw',
				'value'     => function (\common\models\FeedbackEntry $model) {
					$html = "";

					if (!$model->is_viewed) {
						$html .= '<span class="badge badge-success">Новое</span>';
					}

					$html .= Yii::$app->formatter->asDatetime($model->created_at);

					return $html;
				},
			],
			'text:raw',
			[
				'class'          => 'yii\grid\ActionColumn',
				'template'       => '{mark-as-viewed} {delete}',
				'visibleButtons' => [
					'mark-as-viewed' => function (\common\models\FeedbackEntry $model, $key, $index) {
						return $model->is_viewed == 0;
					},
				],
				'buttons'        => [
					'mark-as-viewed' => function ($url, \common\models\FeedbackEntry $model, $key) {
						$title = "Пометить просмотренным";

						$options = [
							'title'      => $title,
							'aria-label' => $title,
							'data-pjax'  => '0',
						];

						$icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-ok"]);

						return Html::a($icon, $url, $options);
					},
				],
			],
		],
	]) ?>

	<?php \yii\widgets\Pjax::end() ?>
</div>