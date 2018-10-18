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

$menuItems = [
	[
		'label' => 'Главная',
		'icon'  => '<i class="fa fa-home" aria-hidden="true"></i>',
		'url'   => ['/site/index'],
	],
	[
		'label' => 'Страницы',
		'icon'  => '<i class="fa fa-file-text-o" aria-hidden="true"></i>',
		'url'   => ['/page-manager/update'],
	],
	[
		'label' => 'Обратная связь',
		'icon'  => '<i class="fa fa-comment-o" aria-hidden="true"></i>',
		'url'   => ['/feedback-entry-manager/index'],
		'count' => \common\models\FeedbackEntry::find()->where(['is_viewed' => 0])->count(),
	],
	[
		'label' => 'Пользователи',
		'icon'  => '<i class="fa fa-users" aria-hidden="true"></i>',
		'items' => [
			[
				'label' => 'Все пользователи',
				'url'   => ['/user/admin/index'],
				'icon'  => '<i class="fa fa-users" aria-hidden="true"></i>',
			],
		],
	],
	[
		'label' => 'Разное',
		'icon'  => '<i class="fa fa-info-circle" aria-hidden="true"></i>',
		'items' => [
			[
				'label' => 'Блоки',
				'icon'  => '<i class="fa fa-square" aria-hidden="true"></i>',
				'url'   => ['/block-manager/index'],
			],
			[
				'label' => 'Редиректы',
				'icon'  => '<i class="fa fa-long-arrow-right" aria-hidden="true"></i>',
				'url'   => ['/redirect-manager/index'],
			],
			[
				'label' => 'Robots.txt',
				'icon'  => '<i class="fa fa-file-text-o" aria-hidden="true"></i>',
				'url'   => ['/robots-txt-manager/index'],
			],
			[
				'label' => 'Настройки',
				'icon'  => '<i class="fa fa-cogs" aria-hidden="true"></i>',
				'url'   => ['/setting-manager/index'],
			],
		],
	],
];

?>
<?php
/** @var \common\components\UserBuddy $userBuddy */
$userBuddy = Yii::$app->userBuddy;

/** @var \common\models\User $userIdentity */
$userIdentity = $user->identity;

$roleList = $userBuddy->getTranslatedRoleListForUser($user->id)
?>
<div>
    <div class="media profile-left">
        <a class="pull-left" href="<?= Url::to(['/user/settings/profile']) ?>">
			<?= Html::img($userModel->profile->getAvatarUrl(48), [
				'class' => 'img-rounded',
				'alt'   => $userModel->username,
			]) ?>
        </a>

        <div class="media-body">
            <h4 class="media-heading">
                <b><?= $userIdentity->displayName ?></b>
                <br/>
				<?= $userIdentity->email ?>
            </h4>
            <small class="text-muted"><?= implode("<br>", $roleList) ?></small>
        </div>
    </div>

    <h5 class="leftpanel-title">Меню</h5>
	<?= \backend\widgets\Menu::widget([
		'items'   => $menuItems,
		'options' => [
			'class' => 'nav nav-pills nav-stacked',
		],
	]) ?>
</div>