<?php

use common\models\User;
use kartik\select2\Select2;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \backend\models\UserSearch $searchModel
 */

$this->title = Yii::t('app', 'Users Roles');

$this->params['breadcrumbs'] = [
	$this->title,
];

/** @var \common\components\UserBuddy $userBuddy */
$userBuddy = Yii::$app->userBuddy;

$userList = User::find()->all();

$userNameList = ArrayHelper::map($userList, 'username', 'username');

$userEmailList = ArrayHelper::map($userList, 'email', 'email');

?>
<div>
	<?php echo GridView::widget([
		'tableOptions' => ['class' => 'table table-hover'],
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			[
				'attribute' => 'username',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'username',
					'data'          => $userNameList,
					'options'       => ['placeholder' => 'Введите логин пользователя ...'],
					'pluginOptions' => [
						'allowClear' => true,
					],
				]),
			],
			[
				'attribute' => 'email',
				'format'    => 'email',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'email',
					'data'          => $userEmailList,
					'options'       => ['placeholder' => 'Введите email пользователя ...'],
					'pluginOptions' => [
						'allowClear' => true,
					],
				]),
			],
			[
				'label'  => 'Роли',
				'format' => 'raw',
				'value'  => function (User $model) use ($userBuddy) {
					$roleList = $userBuddy->getTranslatedRoleListForUser($model->id);

					return implode("<br>", $roleList);
				},
				'filter' => false,
			],
			[
				'class'         => \yii\grid\ActionColumn::className(),
				'template'      => '{update}',
				'filterOptions' => [
					'class' => 'action-column',
				],
				'headerOptions' => [
					'class' => 'action-column',
				],
				'options'       => [
					'class' => 'action-column',
				],
			],
		],
	]) ?>
</div>
