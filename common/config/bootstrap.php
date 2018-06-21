<?php

Yii::$container->set('yii\web\JqueryAsset', [
	'js' => [
		'jquery.min.js',
	],
]);

Yii::$container->set('yii\validators\DateValidator', [
	'format' => 'yyyy-MM-dd',
]);

Yii::$container->set('mranger\ckeditor\CKEditor', [
	'preset' => 'default',
]);

Yii::$container->set('kartik\widgets\DatePicker', [
	'pluginOptions' => [
		'autoclose' => true,
		'format'    => 'yyyy-mm-dd',
	],
]);

Yii::$container->set('kartik\daterange\DateRangePicker', [
	'convertFormat' => true,
	'pluginOptions' => [
		'locale' => [
			'format' => 'Y-m-d',
			/*'daysOfWeek' => ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
			'monthNames' => ["Янвварь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],*/
		],
	],
	'options'       => [
		'class'        => 'form-control',
		'autocomplete' => 'off',
	],
]);

Yii::$container->set('yii\debug\Module', [
	'allowedIPs' => [
		'127.0.0.1', '::1',
	],
]);

Yii::$container->set('yii\gii\Module', [
	'allowedIPs' => [
		'127.0.0.1', '::1',
	],
]);