<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use yii\bootstrap\Nav;

$isRbacManagerAllowed = Yii::$app->user->can(\common\Rbac::PERMISSION_RBAC_MANAGER_ACCESS);

$items = [
	1 => [
		'label' => Yii::t('usuario', 'Users'),
		'url'   => ['/user/admin/index'],
	],
	2 => [
		'label' => Yii::t('usuario', 'Roles'),
		'url'   => ['/user/role/index'],
	],
	3 => [
		'label' => Yii::t('usuario', 'Permissions'),
		'url'   => ['/user/permission/index'],
	],
	4 => [
		'label' => Yii::t('usuario', 'Rules'),
		'url'   => ['/user/rule/index'],
	],
	5 => [
		'label' => Yii::t('usuario', 'Create'),
		'items' => [
			1 => [
				'label' => Yii::t('usuario', 'New user'),
				'url'   => ['/user/admin/create'],
			],
			2 => [
				'label' => Yii::t('usuario', 'New role'),
				'url'   => ['/user/role/create'],
			],
			3 => [
				'label' => Yii::t('usuario', 'New permission'),
				'url'   => ['/user/permission/create'],
			],
			4 => [
				'label' => Yii::t('usuario', 'New rule'),
				'url'   => ['/user/rule/create'],
			],
		],
	],
];

if (!$isRbacManagerAllowed) {
	// Unset List Roles
	unset($items[2]);
	// Unset List Permissions
	unset($items[3]);
	// Unset List Rules
	unset($items[4]);

	// Unset Create Role
	unset($items[5]['items'][2]);
	// Unset Create Permission
	unset($items[5]['items'][3]);
	// Unset Create Rule
	unset($items[5]['items'][4]);
}
?>

<?= Nav::widget(
	[
		'options' => [
			'class' => 'nav-tabs',
			'style' => 'margin-bottom: 15px',
		],
		'items'   => $items,
	]
) ?>
