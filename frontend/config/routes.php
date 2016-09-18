<?php

return [
	// 2016-03-26: Так как у приложения обнулён параметр defaultRoute, необходимо чтобы при обращении к корню
	// URL'а открывалась главная страница
	'' => '/site/index',

	['pattern' => 'sitemap', 'route' => 'sitemap/default/index', 'suffix' => '.xml'],
];
