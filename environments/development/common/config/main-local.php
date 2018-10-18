<?php

return [
	'modules'    => [
		'user' => [
			'mailParams' => [
				'fromEmail' => 'noreply@example.com',
			],
		],
	],
	'components' => [
		'db'                 => [
			'class'             => 'yii\db\Connection',
			'enableSchemaCache' => true,
			'dsn'               => 'mysql:host=localhost;dbname=database_name',
			'username'          => 'root',
			'password'          => '',
			'charset'           => 'utf8',
		],
		'mailer'             => [
			'class'            => 'yii\swiftmailer\Mailer',
			'viewPath'         => '@common/mail',
			'useFileTransport' => true,
		],
		'backendUrlManager'  => [
			'baseUrl' => 'http://site.loc/cp',
		],
		'frontendUrlManager' => [
			'baseUrl' => 'http://site.loc',
		],
	],
];
