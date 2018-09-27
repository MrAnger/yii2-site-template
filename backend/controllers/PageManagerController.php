<?php

namespace backend\controllers;

use common\models\forms\ImageUploadForm;
use common\models\Page;
use common\models\PageGalleryImage;
use common\models\PageTree;
use MrAnger\Yii2_ImageManager\models\Image;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * @author MrAnger
 */
class PageManagerController extends BaseController {
	public function actionUpdate($id = null) {
		if ($id === null) {
			/** @var PageTree $model */
			$root = PageTree::findOne(1);

			/** @var PageTree $pageTree */
			$pageTree = $root->children(1)
				->one();

			if ($pageTree !== null) {
				return $this->redirect(['update', 'id' => $pageTree->page_id]);
			}

			return $this->redirect(['create']);
		}

		$model = $this->findModel($id);

		$request = Yii::$app->request;

		$imageManager = Yii::$app->imageManager;
		$imageUploadForm = new ImageUploadForm();

		$galleryImageDataProvider = new ActiveDataProvider([
			'query'      => PageGalleryImage::find()
				->joinWith('image')
				->where(['page_id' => $model->id])
				->orderBy(['sort_order' => SORT_ASC]),
			'pagination' => false,
		]);

		if (Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->convertParamsToJSON();

			foreach (['selectedEditorIntro', 'selectedEditorContent'] as $key) {
				if ($request->post($key)) {
					$model->setParam($key, $request->post($key));
				}
			}

			$imageUploadForm->load(Yii::$app->request->post());

			$imageUploadForm->file = UploadedFile::getInstance($imageUploadForm, 'file');

			if (Model::validateMultiple([$model, $imageUploadForm])) {
				if ($model->published_at === null) {
					$model->published_at = new Expression("NOW()");
				}

				$transaction = Yii::$app->db->beginTransaction();

				try {
					if (!$model->save(false)) {
						throw new \Exception("Model save is not successfully.");
					}

					// Image Cover
					if ($imageUploadForm->file !== null) {
						$imageEntry = $imageManager->upload($imageUploadForm->file);

						if (!$model->updateAttributes(['image_cover_id' => $imageEntry->id])) {
							$imageManager->deleteImage($imageEntry->id);

							throw new \Exception("Model save with new image is not successfully.");
						}
					}

					$transaction->commit();
				} catch (\Exception $e) {
					$transaction->rollBack();

					throw $e;
				}

				Yii::$app->session->addFlash('success', 'Страница успешно обновлена.');

				return $this->redirect(['update', 'id' => $model->id]);
			}
		}

		return $this->render('update', [
			'model'                    => $model,
			'imageUploadForm'          => $imageUploadForm,
			'menu'                     => $this->getMenuTree(),
			'galleryImageDataProvider' => $galleryImageDataProvider,
			'layoutList'               => $this->getFileList('@frontend/views/layouts'),
			'templateList'             => $this->getFileList('@frontend/views/site'),
		]);
	}

	public function actionCreate($parentId = 1) {
		$model = new Page([
			'is_enabled' => true,
		]);

		/** @var Page $parentModel */
		$parentModel = null;
		if ($parentId !== null) {
			$parentModel = $this->findModel($parentId);

			$model->layout = $parentModel->layout;
		}

		$request = Yii::$app->request;

		$imageManager = Yii::$app->imageManager;
		$imageUploadForm = new ImageUploadForm();

		if (Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			$model->convertParamsToJSON();

			foreach (['selectedEditorIntro', 'selectedEditorContent'] as $key) {
				if ($request->post($key)) {
					$model->setParam($key, $request->post($key));
				}
			}

			$imageUploadForm->load(Yii::$app->request->post());

			$imageUploadForm->file = UploadedFile::getInstance($imageUploadForm, 'file');

			if (Model::validateMultiple([$model, $imageUploadForm])) {
				if ($model->published_at === null) {
					$model->published_at = new Expression("NOW()");
				}

				$transaction = Yii::$app->db->beginTransaction();

				try {
					if (!$model->save(false)) {
						throw new \Exception("Model save is not successfully.");
					}

					// Image Cover
					if ($imageUploadForm->file !== null) {
						$imageEntry = $imageManager->upload($imageUploadForm->file);

						if (!$model->updateAttributes(['image_cover_id' => $imageEntry->id])) {
							$imageManager->deleteImage($imageEntry->id);

							throw new \Exception("Model save with new image is not successfully.");
						}
					}

					// Tree
					$treeModel = new PageTree([
						'page_id' => $model->id,
					]);

					$treeModel->appendTo($parentModel->tree);

					$model->populateRelation('tree', $treeModel);

					$transaction->commit();
				} catch (\Exception $e) {
					$transaction->rollBack();

					throw $e;
				}

				Yii::$app->session->addFlash('success', 'Страница успешно создана.');

				return $this->redirect(['update', 'id' => $model->id]);
			}
		}

		return $this->render('create', [
			'model'           => $model,
			'imageUploadForm' => $imageUploadForm,
			'menu'            => $this->getMenuTree(),
			'layoutList'      => $this->getFileList('@frontend/views/layouts'),
			'templateList'    => $this->getFileList('@frontend/views/site'),
		]);
	}

	public function actionDelete($id) {
		$model = $this->findModel($id);

		$transaction = Yii::$app->db->beginTransaction();

		try {
			foreach ($model->tree->children()->all() as $childrenTree) {
				/** @var PageTree $childrenTree */

				if (!$childrenTree->page->delete()) {
					throw new \Exception("Не удалось удалить все подстраницы.");
				}
			}

			if (!$model->delete()) {
				throw new \Exception("Не удалось удалить страницу.");
			}

			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();

			throw $e;
		}

		return $this->redirect(['update']);
	}

	public function actionMovePage($pageId, $treePageId = null, $nextPageId = null) {
		Yii::$app->response->format = Response::FORMAT_JSON;

		$request = Yii::$app->request;

		$output = ['status' => true];

		$pageTree = PageTree::findOne($pageId);

		if ($pageTree === null) {
			throw new NotFoundHttpException("Страница[ID: $pageId] не найдена.");
		}

		$pageTreeParent = null;

		if ($treePageId !== null) {
			$pageTreeParent = PageTree::findOne($treePageId);

			if ($pageTreeParent === null) {
				throw new NotFoundHttpException("Родительская страница[ID: $treePageId] не найдена.");
			}
		}

		$treePageNext = null;

		if ($nextPageId !== null) {
			$treePageNext = PageTree::findOne($nextPageId);

			if ($treePageNext === null) {
				throw new NotFoundHttpException("Следующая страница[ID: $nextPageId] не найдена.");
			}
		}

		$transaction = Yii::$app->db->beginTransaction();

		try {
			if ($treePageNext) {
				$output['status'] = (boolean)$pageTree->insertBefore($treePageNext);
			} elseif ($pageTreeParent) {
				$output['status'] = (boolean)$pageTree->appendTo($pageTreeParent);
			} else {
				$root = PageTree::findOne(1);

				$output['status'] = (boolean)$pageTree->appendTo($root);
			}

			$transaction->commit();
		} catch (\Exception $e) {
			$transaction->rollBack();

			throw $e;
		}

		return $output;
	}

	public function actionUploadGalleryImage($pageId) {
		Yii::$app->response->format = Response::FORMAT_JSON;

		$pageModel = $this->findModel($pageId);

		$file = UploadedFile::getInstanceByName("file");

		if (!$file instanceof UploadedFile || $file->error == UPLOAD_ERR_NO_FILE)
			throw new BadRequestHttpException("Загрузка файла не удалась.");

		$imageUploadForm = new ImageUploadForm([
			'file' => $file,
		]);

		if (!$imageUploadForm->validate()) {
			throw new BadRequestHttpException(implode("\n", $imageUploadForm->getErrorSummary(true)));
		}

		$imageModel = Yii::$app->imageManager->upload($file);

		if ($imageModel !== null) {
			$link = new PageGalleryImage([
				'page_id'  => $pageModel->id,
				'image_id' => $imageModel->id,
			]);

			return (boolean)$link->save();
		}

		throw new BadRequestHttpException("При сохранении файла возникла непредвиденная ошибка.");
	}

	public function actionGalleryImageSaveOrder($id, $order) {
		Yii::$app->response->format = Response::FORMAT_JSON;

		$output = ['status' => true];

		$model = PageGalleryImage::findOne($id);

		if ($model === null) {
			throw new NotFoundHttpException("Изображение[ID: $id] не найдено.");
		}

		$output['status'] = (boolean)$model->moveToPosition($order);

		return $output;
	}

	public function actionGalleryImageUpdate() {
		Yii::$app->response->format = Response::FORMAT_JSON;

		$output = ['status' => true];

		$id = Yii::$app->request->post('id');

		/** @var PageGalleryImage $model */
		$model = PageGalleryImage::findOne($id);

		if ($model === null) {
			throw new NotFoundHttpException("Изображение[ID: $id] не найдено.");
		}

		$output['status'] = (boolean)($model->image->load(Yii::$app->request->post()) && $model->image->save());

		return $output;
	}

	public function actionGalleryImageDelete($id) {
		Yii::$app->response->format = Response::FORMAT_JSON;

		$output = ['status' => true];

		/** @var PageGalleryImage $model */
		$model = PageGalleryImage::findOne($id);

		if ($model === null) {
			throw new NotFoundHttpException("Изображение[ID: $id] не найдено.");
		}

		$imageModel = $model->image;

		$output['status'] = (boolean)$model->delete();

		if ($output['status']) {
			try {
				Yii::$app->imageManager->deleteImage($imageModel);
			} catch (\Exception $e) {
				Yii::error("Ошбика при удалении изображения[ID: $imageModel->id] у страницы[ID: $model->page_id].\n" .
					$e->getCode() . ": " . $e->getMessage() . "\n" . $e->getTraceAsString()
				);
			}
		}

		return $output;
	}

	public function actionPageCoverDelete($id) {
		Yii::$app->response->format = Response::FORMAT_JSON;

		$model = $this->findModel($id);

		$imageCover = $model->imageCover;

		$result = (boolean)$model->updateAttributes([
			'image_cover_id' => null,
		]);

		if ($result && $imageCover) {
			try {
				Yii::$app->imageManager->deleteImage($imageCover);
			} catch (\Exception $e) {
				Yii::error("Ошбика при удалении изображения[ID: $imageCover->id] у страницы[ID: $model->id].\n" .
					$e->getCode() . ": " . $e->getMessage() . "\n" . $e->getTraceAsString()
				);
			}
		}

		return $this->redirect(['update', 'id' => $model->id]);
	}

	/**
	 * @return array
	 */
	protected function getMenuTree() {
		$rootPages = PageTree::find()
			->joinWith('page')
			->roots()
			->all();

		$treeMaker = function (array $pagesTree, $parentPage, $depth) use (&$treeMaker) {
			/** @var PageTree[] $pagesTree */

			$result = [];

			foreach ($pagesTree as $pageTree) {
				$children = $pageTree->children(1)
					->joinWith(['page'])
					->all();

				$result[] = [
					'id'        => $pageTree->page->id,
					'label'     => $pageTree->page->name,
					'url'       => ['update', 'id' => $pageTree->page->id],
					'urlCreate' => ['create', 'parentId' => $pageTree->page->id],
					'urlDelete' => ['delete', 'id' => $pageTree->page->id],
					'items'     => $treeMaker($children, $pageTree, $depth + 1),
				];
			}

			return $result;
		};

		$pages = $treeMaker($rootPages, null, 0);

		return ArrayHelper::getValue($pages, '0.items');
	}

	/**
	 * @param string $dir
	 *
	 * @return array
	 */
	protected function getFileList($dir) {
		$fileList = FileHelper::findFiles(Yii::getAlias($dir), [
			'recursive' => false,
			'filter'    => function ($path) {
				$fileName = basename($path);

				if (substr($fileName, 0, 1) == "_") {
					return false;
				}

				return true;
			},
		]);

		array_walk($fileList, function (&$item) {
			$item = basename($item);
		});

		sort($fileList);

		array_walk($fileList, function (&$item) use ($dir) {
			$item = [
				'name' => basename($item, '.php'),
				'path' => "$dir/$item",
			];
		});

		$fileList = ArrayHelper::map($fileList, 'path', 'name');

		return $fileList;
	}

	/**
	 * @param mixed $pk
	 *
	 * @return Page
	 *
	 * @throws NotFoundHttpException
	 */
	protected function findModel($pk) {
		if (($model = Page::findOne($pk)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}
