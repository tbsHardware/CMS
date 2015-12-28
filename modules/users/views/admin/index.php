<?php
/* @var \yii\data\ActiveDataProvider $dataProvider */
/* @var \yii\base\Model $searchModel */

use yii\widgets\Pjax;
use yii\helpers\Html;
use app\modules\admin\widgets\grid\GridView;
use yii\jui\DatePicker;

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;

function getDateFilter($model, $attribute, $placeholder) {

    $button = Html::button('<i class="fa fa-calendar"></i>', ['class' => 'btn btn-sm default', 'onclick' => '
        $(this).closest("div").children("input").datepicker("show")
    ']);

    $dateFilter = Html::beginTag('div', ['class' => 'input-group date date-picker']);
    $dateFilter .= DatePicker::widget([
        'model'      => $model,
        'attribute'  => $attribute,
        'dateFormat' => 'php:d.m.Y',
        'options' => [
            'placeholder' => $placeholder,
            'readonly' => true,
            'class' => 'form-control form-filter input-sm',
        ],
    ]);
    $dateFilter .= Html::tag('span', $button, ['class' => 'input-group-btn']);
    $dateFilter .= Html::endTag('div');

    return $dateFilter;
}
?>
<div class="row">
    <div class="col-md-12">
        <?php Pjax::begin() ?>

        <?= GridView::widget([
            'dataProvider' 	=> $dataProvider,
            'filterModel'  	=> $searchModel,
            'columns' => [
                [
                    'attribute' => 'id',
                    'label' => 'ID',
                    'options' => ['width' => '100'],
                ],
                [
                    'attribute' => 'username',
                    'label' => 'Имя пользователя',
                    'format' => 'raw',
                    'value' => function($model) {
                        if (Yii::$app->user->can("users_update")) {
                            return Html::a(Html::encode($model->username), ['update', 'id' => $model->id],
                                ['class' => 'update', 'data-pjax' => 0]);
                        } else {
                            return Html::encode($model->username);
                        }
                    },
                ],
                'email',
                [
                    'attribute' => 'created_at',
                    'label' => 'Дата регистрации',
                    'format' =>  ['date', 'dd MMMM yyyy - HH:mm'],
                    'options' => ['width' => '200'],
                    'filter' => getDateFilter($searchModel, 'created_at_from', 'От') .
                        '<div class="margin-bottom-5"></div>' .
                        getDateFilter($searchModel, 'created_at_to', 'До'),
                ],
                [
                    'attribute' => 'registration_ip',
                    'label' => 'IP регистрации',
                    'options' => ['width' => '175'],
                ],
                [
                    'attribute' => 'confirmed_at',
                    'label' => 'Активация',
                    'value' => function ($model) {
                        if ($model->isConfirmed) {
                            return '<div class="text-center">Активен</div>';
                        } else {
                            return Html::a('Активировать', ['confirm', 'id' => $model->id], [
                                'class' => 'btn green btn-xs btn-block',
                                'data-method' => 'post',
                                'data-confirm' => 'Вы уверены, что хотите активировать пользователя?',
                            ]);
                        }
                    },
                    'format' => 'raw',
                    'options' => ['width' => '175'],
                    'visible' => Yii::$app->getModule('users')->enableConfirmation &&
                        Yii::$app->user->can("users_confirm"),
                ],
                [
                    'attribute' => 'blocked_at',
                    'label' => 'Блокировка',
                    'value' => function ($model) {
                        if ($model->isBlocked) {
                            return Html::a('Разблокировать', ['unblock', 'id' => $model->id], [
                                'class' => 'btn btn-xs red btn-block',
                                'data-method' => 'post',
                                'data-confirm' => 'Вы уверены, что хотите разблокировать пользователя?',
                            ]);
                        } else {
                            return Html::a('Заблокировать', ['block', 'id' => $model->id], [
                                'class' => 'btn btn-xs red btn-block btn-outline',
                                'data-method' => 'post',
                                'data-confirm' => 'Вы уверены, что хотите заблокировать пользователя?',
                            ]);
                        }
                    },
                    'format' => 'raw',
                    'options' => ['width' => '175'],
                    'visible' => function ($model) {
                        if ($model->isBlocked) {
                            return Yii::$app->user->can("users_unblock");
                        } else {
                            return Yii::$app->user->can("users_block");
                        }
                    }
                ],
                [
                    'header' => 'Действия',
                    'class' => 'app\modules\admin\widgets\grid\ActionColumn',
                    'template' => '{delete}',
                    'options' => ['width' => '50'],
                    'visible' => Yii::$app->user->can("users_delete"),
                ],
            ],
            'headerButtons' => [
                [
                    'label' => 'Добавить нового <i class="fa fa-plus"></i>',
                    'url' => 'add',
                    'options' => ['class' => 'btn sbold green'],
                    'visible' => Yii::$app->user->can("users_add"),
                ],
            ],
        ]); ?>

        <?php Pjax::end() ?>
    </div>
</div>
