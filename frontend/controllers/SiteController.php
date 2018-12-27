<?php

namespace frontend\controllers;

use common\models\Page;
use common\models\PageTree;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
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
		$page = $this->findModelPage(['full_url' => 'index']);

		return $this->displayPage($page);
	}

	public function actionViewPageBySlug($full_url) {
		/** @var Page $page */
		$page = $this->findModelPage(['full_url' => $full_url]);

		return $this->displayPage($page);
	}

	/**
	 * @param Page $page
	 *
	 * @return string
	 */
	protected function displayPage($page) {
		if ($page->redirect_url) {
			return $this->redirect($page->redirect_url, $page->redirect_code);
		}

		$this->view->params['pageModel'] = $page;

		$this->pageRegisterMeta($page);
		$this->pageRegisterBreadcrumbs($page);

		if (!empty($page->layout)) {
			$this->layout = $page->layout;
		}

		$dataProvider = new ActiveDataProvider([
			'query'      => $page->tree
				->children(1)
				->joinWith('page')
				->andWhere(['page.is_enabled' => 1])
				->orderBy(['page.published_at' => SORT_DESC]),
			'pagination' => [
				'pageSize'       => 9,
				'forcePageParam' => false,
				'pageSizeParam'  => false,
			],
		]);

		return $this->render((($page->file_template) ? $page->file_template : 'page-default'), [
			'page'            => $page,
			'dataProvider'    => $dataProvider,
			'similarPageList' => $this->getSimilarPageList($page),
		]);
	}

	/**
	 * @param Page $page
	 */
	protected function pageRegisterMeta($page) {
		$title = $page->name;

		if (!empty($page->meta_title)) {
			$title = $page->meta_title;
		}

		$this->view->title = $title;

		$this->view->registerMetaTag([
			'name'    => 'description',
			'content' => $page->meta_description,
		], 'description');

		$this->view->registerMetaTag([
			'name'    => 'keywords',
			'content' => $page->meta_keywords,
		], 'keywords');
	}

	/**
	 * @param Page $page
	 */
	protected function pageRegisterBreadcrumbs($page) {
		/** @var PageTree[] $parents */
		$parents = $page->tree
			->parents()
			->joinWith('page')
			->all();

		foreach ($parents as $parent) {
			if ($parent->page_id == 1) {
				continue;
			}

			$this->view->params['breadcrumbs'][] = [
				'label' => $parent->page->name,
				'url'   => $parent->page->getFrontendUrl(),
			];
		}

		if ($page->slug != 'index') {
			$this->view->params['breadcrumbs'][] = [
				'label' => $page->name,
			];
		}
	}

	/**
	 * @param Page $page
	 *
	 * @return Page[]
	 */
	protected function getSimilarPageList($page) {
		$parentTree = $page->tree
			->parents(1)
			->one();

		if ($parentTree === null) {
			return [];
		}

		$similarList = $parentTree->children(1)
			->joinWith('page')
			->andWhere([
				'AND',
				['=', 'page.is_enabled', 1],
				['<>', 'page_id', $page->id],
				['<>', 'page.full_url', 'index'],
			])
			->orderBy(['page.published_at' => SORT_DESC])
			->limit(4)
			->all();

		return ArrayHelper::getColumn($similarList, 'page');
	}

	/**
	 * @param mixed $pk
	 *
	 * @return Page
	 *
	 * @throws NotFoundHttpException
	 */
	protected function findModelPage($pk) {
		$model = Page::find()
			->where($pk)
			->one();

		if ($model !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}