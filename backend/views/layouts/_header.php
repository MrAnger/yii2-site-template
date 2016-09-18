<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \yii\web\View $this
 */

$user = Yii::$app->user;

?>

<div class="headerwrapper">
	<div class="header-left">
		<a href="<?= Yii::$app->homeUrl ?>" class="site-logo">
			<!--<img src="/images/logo_small.png" alt="" style="width: 100px;"/>-->
		</a>

		<div class="pull-right">
			<a href="" class="menu-collapse">
				<i class="fa fa-bars"></i>
			</a>
		</div>
	</div>
	<!-- header-left -->

	<div class="header-right">
		<div class="pull-right">
			<div class="btn-group btn-group-option">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
					<i class="fa fa-caret-down"></i>
				</button>

				<ul class="dropdown-menu pull-right" role="menu">
					<li>
						<a href="<?= Url::to(['/user/logout']) ?>" data-method="post">
							<i class="glyphicon glyphicon-log-out"></i>Выйти
						</a>
					</li>
				</ul>
			</div>
		</div>
		<!-- pull-right -->

	</div>
	<!-- header-right -->

</div>
<!-- headerwrapper -->