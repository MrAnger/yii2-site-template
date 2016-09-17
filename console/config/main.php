<?php

$params = array_merge(
	require(__DIR__ . '/../../common/config/params.php'),
	require(__DIR__ . '/../../common/config/params-local.php'),
	require(__DIR__ . '/params.php'),
	require(__DIR__ . '/params-local.php')
);

return [
	'id'                  => 'app-console',
	'basePath'            => dirname(__DIR__),
	'controllerNamespace' => 'console\controllers',
	'bootstrap'           => ['log'],
	'params'              => $params,
	'components'          => [],
];
