<?php
use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="jumbotron">

    <p class="lead"><?= $message ?></p>

    <p><?= Html::a('Вернуться на главную', Url::home(), array('class' => 'btn btn-lg btn-success')) ?></p>
</div>
