<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \common\models\Page $model
 * @var \yii\data\ActiveDataProvider $galleryImageDataProvider
 */

\common\assets\SortableJsAsset::register($this);

$imageManager = Yii::$app->imageManager;

$urlGalleryImageSaveOrder = Url::to(['gallery-image-save-order']);
$urlGalleryImageDelete = Url::to(['gallery-image-delete']);

$this->registerJs(<<<JS
function updateGalleryImageList(){
    $.pjax({
        url: window.location.href,
	    container: "#pjax-gallery-image-list",
		scrollTo: false,
	    timeout: 8000
    });
    
    setTimeout(initSortableGalleryImageList, 1500);
};

function initSortableGalleryImageList() {
    Sortable.create($('#gallery-image-list')[0], {
        onEnd: function(e) {
            var currentItem = $(e.item);
            
            var data = {
                id: currentItem.data('key'),
                order: currentItem.index() + 1
            };
                
            $.get("$urlGalleryImageSaveOrder", data, function(response) {
                if(!response.status) {
                    alert("Не удалось сохранить данные.");
                    
                    location.reload();
                }
            }).fail(function(response) {
                console.log(response);
                
                alert(response.responseText);
                
                location.reload();
            });
        }
    });
};

$(document).on('click', '.page-image-gallery-item .actions a', function(e) {
    e.preventDefault();
    
    var el = $(this),
        action = el.data('action');
    
    switch (action){
        case 'delete':
            if(confirm("Вы действительно хотите удалить изображение")) {
                $.get("$urlGalleryImageDelete", {id: el.closest('.page-image-gallery-item').data('key')}, function(response) {
                    updateGalleryImageList();
                }).fail(function(response) {
                    console.log(response);
                    
                    alert("Не удалось удалить изображение.");
                    
                    updateGalleryImageList();
                });
            }
            break;
            
            case 'update':
                var modalUpdate = $('#modal-gallery-image-update');
                
                modalUpdate.data('image-id', el.closest('.page-image-gallery-item').data('key'));

                modalUpdate.modal('show');
                break;
    }
});
JS
);

$this->registerJs("initSortableGalleryImageList();");
?>
<div id="tab-panel-images-gallery" class="tab-pane fade">
	<?= \common\widgets\Dropzone::widget([
		'uploadUrl'       => ['upload-gallery-image', 'pageId' => $model->id],
		'dropzoneOptions' => [
			'acceptedFiles'      => "image/*",
			'dictDefaultMessage' => 'Выберите изображения для загрузки',
		],
		'onQueueComplete' => new \yii\web\JsExpression('function(){updateGalleryImageList();}'),
	]) ?>

	<?php \yii\widgets\Pjax::begin([
		'id'      => 'pjax-gallery-image-list',
		'timeout' => 8000,
	]) ?>

	<?= \yii\widgets\ListView::widget([
		'id'               => 'gallery-image-list',
		'dataProvider'     => $galleryImageDataProvider,
		'summary'          => false,
		'emptyText' => false,
		'options'          => [
			'class' => 'row',
		],
		'itemOptions'      => function (\common\models\PageGalleryImage $model, $key, $index, $widget) {
			$output = [
				'class'            => 'col-md-3 page-image-gallery-item',
				'data-title'       => $model->image->title,
				'data-description' => $model->image->description,
			];

			return $output;
		},
		'itemView'         => function (\common\models\PageGalleryImage $model, $key, $index, $widget) use ($imageManager) {
			$html = Html::a(
				Html::img($imageManager->getThumbnailUrl($model->image, 'backend-page-gallery-list-cover')),
				$imageManager->getOriginalUrl($model->image),
				[
					'class'     => 'img-wrapper',
					'target'    => '_blank',
					'data-pjax' => 0,
				]
			);

			$html .= <<<HTML
<div class="actions">
    <a href="#" data-pjax="0" title="Редактировать описание" data-action="update">
        <i class="glyphicon glyphicon-pencil" aria-hidden="true"></i>
    </a>
    <a href="#" data-pjax="0" title="Удалить" data-action="delete">
        <i class="glyphicon glyphicon-trash" aria-hidden="true"></i>
    </a>
</div>
HTML;

			return $html;
		},
	]) ?>

	<?php \yii\widgets\Pjax::end() ?>
</div>