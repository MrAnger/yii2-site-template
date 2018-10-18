<?php

/**
 * @var yii\web\View $this
 */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

\common\assets\SortableJsAsset::register($this);

$urlMovePage = Url::to(['move-page']);

$this->registerJs(<<<JS
(function() {
    var elements = $('.js-draggable-container');
    
    elements.each(function(index, el) {
        Sortable.create(el, {
            group: "group-pages",
            onStart: function(e) {
                elements.find(":visible").addClass('drag-processing');
            },
            onEnd: function(e) {
                elements.removeClass('drag-processing');

                var currentLi = $(e.item),
                    currentItem = currentLi.children('.item').eq(0),
                    parentItem = currentLi.parents('ul').eq(0).prev(),
                    nextItem = currentLi.next().find('.item').eq(0);
                
                var data = {
                    pageId: currentItem.data('id')
                };
                
                if(parentItem.data('id')) {
                    data.treePageId = parentItem.data('id');
                }
                
                if(nextItem.data('id')) {
                    data.nextPageId = nextItem.data('id');
                }
                
                $.get("$urlMovePage", data, function(response) {
                  if(!response.status) {
                      alert("Не удалось сохранить данные.");
                      
                      location.reload();
                  }
                  
                  fixListIcons();
                }).fail(function(response) {
                    console.log(response);
                    
                    alert(response.responseText);
                    
                    location.reload();
                });
            }
        });
    });
    
    function fixListIcons() {
        var menu = $('.pages-menu');
        
        menu.find('li').each(function(index, li) {
            var hasSubItems = false,
                li = $(li);
            
            if(li.find('> ul').length && li.find('> ul > li').length) {
                hasSubItems = true;
            }
            
            if(hasSubItems) {
                li.addClass('parent');
            }else{
                li.removeClass('parent open');
            }
        });
    }
})();
JS
);

$this->registerJs(<<<JS
(function() {
    var menu = $('.pages-menu');
    
    menu.on('click', 'li.parent .item', function(e) {
        var el = $(this);
        
        if(this == e.target) {
            el.parent('li').toggleClass('open');
        }
    });
})();
JS
);
?>
<div>
	<?= \backend\widgets\PagesMenu::widget([
		'items' => $menu,
	]) ?>

	<?= Html::a('Создать новую страницу', ['create'], [
		'class' => 'btn btn-success btn-xs btn-block',
		'style' => 'margin-top: 15px;',
	]) ?>
</div>
