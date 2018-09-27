<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 */

$dummyModel = new \MrAnger\Yii2_ImageManager\models\Image();

$this->registerJs(<<<JS
(function() {
    var modal = $('#modal-gallery-image-update'),
        form = modal.find('form');

    modal.on('show.bs.modal', function (e) {
        var imageId = modal.data('image-id'),
            imageEl = $('.page-image-gallery-item[data-key='+imageId+']');
        
        form.find('input[name*=id]')
            .val('')
            .val(imageId);
        
        form.find('input[name*=title]')
            .val('')
            .val(imageEl.data('title'));
        
        form.find('textarea[name*=description]')
            .val('')
            .val(imageEl.data('description'));
        
        form.yiiActiveForm('resetForm');
    });
    
    form.on('beforeSubmit', function (e) {
        $.post(form.attr('action'), form.serialize(), function (response) {
            if (response.status) {
                modal.modal('hide');
                
                updateGalleryImageList();
            }else{
                alert("Не удалось сохранить данные.");
            }
        }).fail(function(response) {
            console.log(response);
            
            alert("Не удалось сохранить данные.");
        });
        
        return false;
    });
})();
JS
);
?>
<div id="modal-gallery-image-update" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Изменение изображения</h4>
            </div>

            <div class="modal-body">
				<?php $form = \yii\widgets\ActiveForm::begin([
					'enableClientValidation' => true,
					'action'                 => ['gallery-image-update'],
				]) ?>

				<?= Html::hiddenInput('id') ?>

				<?= $form->field($dummyModel, 'title')
					->textInput(['maxlength' => true]) ?>

				<?= $form->field($dummyModel, 'description')
					->textarea([
						'maxlength' => true,
						'rows'      => 7,
					]) ?>

				<?php $form->end() ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <button form="<?= $form->id ?>" type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</div>
