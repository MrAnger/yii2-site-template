<?php

namespace frontend\components;

use common\models\Page;
use yii\web\UrlRuleInterface;
use yii\base\BaseObject;
use Yii;

class PageUrlRule extends BaseObject implements UrlRuleInterface {
	public function createUrl($manager, $route, $params) {
		if ($route === 'site/view-page-by-slug') {
			if (isset($params['full_url'])) {
				return $params['full_url'] . "/";
			}
		}

		return false;
	}

	public function parseRequest($manager, $request) {
		$pathInfo = $request->getPathInfo();

		$fullUrl = $pathInfo;

		$withoutLastSlash = false;

		if (substr($fullUrl, -1) != "/") {
			$withoutLastSlash = true;
		} else {
			$fullUrl = substr($fullUrl, 0, strlen($fullUrl) - 1);
		}

		$isExists = Page::find()
			->where(['full_url' => $fullUrl])
			->exists();

		if ($isExists && $withoutLastSlash) {
			Yii::$app->response->redirect("/$fullUrl/", 301);
			Yii::$app->response->send();
		}

		if ($isExists) {
			return [
				'site/view-page-by-slug', [
					'full_url' => $fullUrl,
				],
			];
		}

		return false;
	}
}