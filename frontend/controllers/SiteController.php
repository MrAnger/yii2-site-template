<?php

namespace frontend\controllers;

use common\models\Page;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * @author MrAnger
 */
class SiteController extends BaseController {
	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}

	public function actionIndex() {
		/** @var Page $page */
		$page = $this->findModelPage(['slug' => 'index']);

		return $this->displayPage($page);
	}

	public function actionViewPageBySlug($slug) {
		/** @var Page $page */
		$page = $this->findModelPage(['slug' => $slug]);

		return $this->displayPage($page);
	}

	/**
	 * @param Page $page
	 *
	 * @return string
	 */
	protected function displayPage($page) {
		$this->view->registerMetaTag([
			'name'    => 'description',
			'content' => $page->meta_description,
		], 'description');

		$this->view->registerMetaTag([
			'name'    => 'keywords',
			'content' => $page->meta_keywords,
		], 'keywords');

		$title = $page->name;

		if (!empty($page->meta_title)) {
			$title = $page->meta_title;
		}

		$this->view->title = $title;

		if (!empty($page->layout)) {
			$this->layout = $page->layout;
		}

		$fileTemplate = 'page-default';

		if (!empty($page->file_template)) {
			$fileTemplate = $page->file_template;
		}

		return $this->render($fileTemplate, [
			'page' => $page,
		]);
	}

	/**
	 * @param mixed $pk
	 *
	 * @return Page
	 *
	 * @throws NotFoundHttpException
	 */
	protected function findModelPage($pk) {
		if (($model = Page::findOne($pk)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}