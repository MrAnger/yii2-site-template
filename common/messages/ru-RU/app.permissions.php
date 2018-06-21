<?php

use common\Rbac;

return [
	Rbac::PERMISSION_CONTROL_PANEL_ACCESS   => 'Доступ в панель управления(админку)',
	Rbac::PERMISSION_USER_MANAGER_ACCESS    => 'Доступ в раздел управления пользователями',
	Rbac::PERMISSION_RBAC_MANAGER_ACCESS    => 'Доступ в раздел управления системой ролей',
	Rbac::PERMISSION_SWITCH_IDENTITY_ACCESS => 'Разрешение авторизовываться под пользователем',

	Rbac::PERMISSION_USER_EDIT        => 'Разрешение редактировать пользователей',
	Rbac::PERMISSION_USER_BLOCK       => 'Разрешение блокировать/разблокировать пользователей',
	Rbac::PERMISSION_USER_DELETE      => 'Разрешение удалять пользователей',
	Rbac::PERMISSION_USER_ROLE_CHANGE => 'Разрешение менять роли пользователей',
];
