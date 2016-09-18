<?php

/**
 * @var \yii\web\View $this
 * @var string $content
 */

use backend\assets\BackendAsset;
use backend\assets\ThemeAsset;
use backend\assets\BowerAsset;
use yii\helpers\Html;

BowerAsset::register($this);
ThemeAsset::register($this);
BackendAsset::register($this);
?>
<?php $this->beginContent('@app/views/layouts/plain.php') ?>
<header>
	<?= $this->render('_header') ?>
</header>

<section>
	<div class="mainwrapper">
		<div class="leftpanel">
			<?= $this->render('_left-panel') ?>
		</div>

		<div class="mainpanel">
			<div class="pageheader">
				<?= $this->render('_page-header') ?>
			</div>
			<div class="contentpanel site-body">
				<?= \common\widgets\Alert::widget() ?>
				<?= $content ?>
			</div>
		</div>
	</div>
</section>
<?php $this->endContent() ?>
