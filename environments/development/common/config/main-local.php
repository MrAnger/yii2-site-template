<?php

return [
	'components' => [
		'request' => [
			// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
			'cookieValidationKey' => '',
		],
		'db'      => [
			'class'    => 'yii\db\Connection',
			'dsn'      => 'mysql:host=localhost;dbname=database_name',
			'username' => 'root',
			'password' => '',
			'charset'  => 'utf8',
		],
		'mailer'  => [
			'class'            => 'yii\swiftmailer\Mailer',
			'viewPath'         => '@common/mail',
			'useFileTransport' => true,
		],
	],
];
