<?php

namespace console\controllers;

use common\models\Page;
use Yii;
use yii\console\Controller;

class MainController extends Controller {
	/**
	 * php yii main/set-full-url-for-pages
	 */
	public function actionSetFullUrlForPages() {
		Page::updateFullUrl(Page::findOne(1));
	}
}