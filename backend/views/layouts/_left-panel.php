<?php

use common\Rbac;
use common\models\Feedback;
use common\models\Review;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 */

$user = Yii::$app->user;

/** @var \common\models\User $userModel */
$userModel = $user->identity;

$mainMenuItems = [
	[
		'label' => 'Пользователи',
		'icon'  => 'fa fa-users',
		'items' => [
			[
				'label' => 'Пользователи',
				'url'   => ['/user/admin/index'],
				'icon'  => 'fa fa-users',
			],
			[
				'label' => 'Роли пользователей',
				'url'   => ['/user-manager/index'],
			],
		],
	],
];

?>
<?php
/** @var \common\components\UserBuddy $userBuddy */
$userBuddy = Yii::$app->userBuddy;

/** @var \common\models\User $userIdentity */
$userIdentity = $user->getIdentity();

$roleList = $userBuddy->getTranslatedRoleListForUser($user->id)
?>
<div class="media profile-left">
	<a class="pull-left" href="<?= Url::to(['/user/settings/profile']) ?>">
		<?= Html::img($userModel->profile->getAvatarUrl(48), [
			'class' => 'img-rounded',
			'alt'   => $userModel->username,
		]) ?>
	</a>

	<div class="media-body">
		<h4 class="media-heading"><b><?= $userIdentity->displayName ?></b><br/><?= $userIdentity->email ?></h4>
		<small class="text-muted"><?= implode(", ", $roleList) ?></small>
	</div>
</div>
<!-- media -->

<h5 class="leftpanel-title">Меню</h5>
<ul class="nav nav-pills nav-stacked">
	<li>
		<a href="<?= Yii::$app->homeUrl ?>">
			<i class="fa fa-home"></i> <span>Главная страница</span>
		</a>
	</li>
	<?php foreach ($mainMenuItems as $item): ?>
		<?php if (isset($item['items'])): ?>
			<li class="parent">
				<a href="#">
					<i class="<?= ArrayHelper::getValue($item, 'icon', 'fa fa-bars') ?>"></i>
					<span><?= $item['label'] ?></span>

					<?php if (ArrayHelper::getValue($item, 'count', 0) > 0): ?>
						<i class="badge"><?= ArrayHelper::getValue($item, 'count', 0) ?></i>
					<?php endif; ?>
				</a>

				<ul class="children">
					<?php foreach ($item['items'] as $item): ?>
						<li>
							<a href="<?= Url::to($item['url']) ?>">
								<i class="<?= ArrayHelper::getValue($item, 'icon', 'fa fa-home') ?>"></i>
								<span><?= $item['label'] ?></span>

								<?php if (ArrayHelper::getValue($item, 'count', 0) > 0): ?>
									<i class="badge"><?= ArrayHelper::getValue($item, 'count', 0) ?></i>
								<?php endif; ?>
							</a>
						</li>
					<?php endforeach ?>
				</ul>
			</li>
			<?php continue;endif ?>
		<li>
			<a href="<?= Url::to($item['url']) ?>">
				<i class="<?= ArrayHelper::getValue($item, 'icon', 'fa fa-home') ?>"></i>
				<span><?= $item['label'] ?></span>
			</a>
		</li>
	<?php endforeach ?>
</ul>