<?php
/* @var \yii\data\ArrayDataProvider $dataProvider */
/* @var \yii\base\Model $searchModel */

use yii\widgets\Pjax;
use app\modules\admin\widgets\grid\GridView;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

$this->title = 'Роли';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <?php Pjax::begin() ?>

        <?= GridView::widget([
            'dataProvider' 	=> $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'description',
                    'label' => 'Название',
                ],
                [
                    'attribute' => 'name',
                    'label' => 'Алиас',
                ],
                [
                    'class'     => DataColumn::className(),
                    'label'     => Yii::t('db_rbac', 'Разрешенные доступы'),
                    'format'    => ['html'],
                    'value'     => function($data) { return implode('<br>',array_keys(ArrayHelper::map(Yii::$app->authManager->getPermissionsByRole($data->name), 'description', 'description')));}
                ],
            ],
        ]); ?>

        <?php Pjax::end() ?>
    </div>
</div>
