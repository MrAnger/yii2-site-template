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
			'dsn'               => 'mysql:host=localhost;dbname=database_name',
			'username'          => 'root',
			'password'          => '',
			'charset'           => 'utf8',
		],
		'mailer'             => [
			'class'     => 'yii\swiftmailer\Mailer',
			'viewPath'  => '@common/mail',
			'transport' => [
				'class' => 'Swift_SmtpTransport',

				'host'       => 'smtp.gmail.com',
				'username'   => '',
				'password'   => '',
				'port'       => '587',
				'encryption' => 'tls',
			],
		],
		'reCaptcha'          => [
			'siteKey' => '',
			'secret'  => '',
		],
		'backendUrlManager'  => [
			'baseUrl' => 'http://site.ru/cp',
		],
		'frontendUrlManager' => [
			'baseUrl' => 'http://site.ru',
		],
	],
];
