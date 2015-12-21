<?php
/* @var \yii\data\ActiveDataProvider $dataProvider */
/* @var \yii\base\Model $searchModel */

use yii\widgets\Pjax;
use app\modules\admin\widgets\grid\GridView;

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <?php Pjax::begin() ?>

        <?= GridView::widget([
            'dataProvider' 	=> $dataProvider,
            'filterModel'  	=> $searchModel,
            'columns' => [
                'id',
                'username',
                'email',
                'created_at',
                'registration_ip',
                'confirmed_at',
                'blocked_at',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                ],
            ],
            'headerButtons' => [
                [
                    'label' => 'Добавить нового <i class="fa fa-plus"></i>',
                    'url' => 'add',
                    'options' => ['class' => 'btn sbold green'],
                ],
            ],
        ]); ?>

        <?php Pjax::end() ?>

    </div>
</div>
