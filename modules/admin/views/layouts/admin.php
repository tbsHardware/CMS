<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\modules\admin\assets\AdminAsset;
use wfcreations\simplelineicons\SLI;

$bundle = AdminAsset::register($this);
$adminModule = Yii::$app->getModule('admin');
$url = Yii::$app->request->url;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
<?php $this->beginBody() ?>
<!-- BEGIN PAGE HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <div class="page-header-inner">
        <div class="page-logo">
            <?= Html::a(Html::img($bundle->baseUrl . '/img/logo.png', ['class' => 'logo-default']), Url::to(['/admin'])); ?>
            <div class="menu-toggler sidebar-toggler"></div>
        </div>
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"></a>
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <!-- Здесь должен быть автар -->
                        <img alt="" class="img-circle" src="http://www.keenthemes.com/preview/metronic/theme/assets/layouts/layout/img/avatar3_small.jpg" />
                        <span class="username username-hide-on-mobile"><?= Yii::$app->user->identity->username ?></span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li><?= Html::a(SLI::icon('key') . 'Выход', Url::to(['dashboard/logout']),
                                ['data-method' => 'post']) ?></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- END PAGE HEADER -->
<div class="clearfix"></div>
<!-- BEGIN PAGE CONTAINER -->
<div class="page-container">
    <!-- BEGIN SIDEBAR -->
    <?= \app\modules\admin\widgets\nav\PageSidebar::widget(); ?>
    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-bar">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    'itemTemplate' => "<li>{link} <i class='fa fa-circle'></i></li>\n",
                    'activeItemTemplate' => "<li><span>{link}</span></li>\n",
                    'options' => [
                        'class' => 'page-breadcrumb',
                    ],
                ]) ?>
            </div>
            <h3 class="page-title"><?= $this->title ?></h3>
            <?= $content ?>
        </div>
    </div>
    <!-- END CONTENT -->
</div>
<!-- END PAGE CONTAINER -->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
