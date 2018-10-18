<?php

/**
 * @var \yii\web\View $this
 */

use yii\helpers\Html;

$model = new \common\models\forms\CallbackForm();

$this->registerJs(<<<JS
(function() {
    var modal = $('#callback-modal'),
        form = modal.find('form');
    
    form.on('beforeSubmit', function(e) {
        e.preventDefault();

        $.post(form.attr('action'), form.serialize(), function(response) {
            if(response.status) {
                form.find('input[type=hidden][name*=captcha]').val('');
                
                modal.modal('hide');
                
                showSuccessModal();
            }else{
                var errorList = ["Не удалось отправить данные формы."];
                
                if(response.errors && !$.isEmptyObject(response.errors)) {
                    $.each(response.errors, function(index, errors) {
                        errorList = errorList.concat(errors);
                    });
                }
                
                alert(errorList.join(LF));
            }
        }).fail(function(response) {
            console.log(response);
            
            alert(response.responseText);
        });
        
        return false;
    });
})();
JS
);
?>
<!-- Modal callback start -->
<div id="callback-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Заказать звонок</h4>
            </div>

            <div class="modal-body">
				<?php $form = \yii\widgets\ActiveForm::begin([
					'enableClientValidation' => false,
					'enableAjaxValidation'   => true,
					'validationUrl'          => ['/ajax/feedback/validate-form', 'type' => $model->getType()],
					'action'                 => ['/ajax/feedback/create', 'type' => $model->getType()],
				]) ?>

				<?= $form->field($model, 'phone')
					->label(false)
					->widget(\yii\widgets\MaskedInput::className(), [
						'mask'    => '+7 999 999-99-99',
						'options' => [
							'id'          => Html::getInputId($model, 'phone') . "-" . uniqid(),
							'type'        => 'tel',
							'placeholder' => 'Ваш телефон',
                            'class' => 'form-control',
						],
					])
				?>

				<?php if ($model->isUseAcceptPrivacy): ?>
					<?= $form->field($model, 'acceptPrivacy')
						->checkbox([
							'label' => "Я ознакомился и согласен с <a href=\"#\" target='_blank'>политикой о конфиденциальности и передаче персональных данных</a>",
						]) ?>
				<?php endif; ?>

				<?php if ($model->isUseCaptcha): ?>
					<?= $form->field($model, 'captcha')
						->label(false)
						->widget(\himiklab\yii2\recaptcha\ReCaptcha::className(), [
							'theme' => \himiklab\yii2\recaptcha\ReCaptcha::THEME_LIGHT,
						]) ?>
				<?php endif; ?>

				<?php $form::end() ?>
            </div>

            <div class="modal-footer">
                <button type="submit" form="<?= $form->id ?>" class="btn btn-primary">Отправить заявку</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal callback end -->
