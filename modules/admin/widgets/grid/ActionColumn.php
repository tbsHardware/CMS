<?php

namespace app\modules\admin\widgets\grid;

use Yii;
use yii\helpers\Html;

class ActionColumn extends \yii\grid\ActionColumn
{
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                $options = array_merge([
                    'aria-label' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-xs btn-outline grey-salsa',
                ], $this->buttonOptions);
                return Html::a('<span class="fa fa-search"></span> ' . Yii::t('yii', 'View'), $url, $options);
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                $options = array_merge([
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-xs btn-outline grey-salsa',
                ], $this->buttonOptions);
                return Html::a('<span class="fa fa-pencil"></span> ' . Yii::t('yii', 'Update'), $url, $options);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                $options = array_merge([
                    'aria-label' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                    'class' => 'btn btn-xs red btn-outline',
                ], $this->buttonOptions);
                return Html::a('<span class="fa fa-trash-o"></span> ' . Yii::t('yii', 'Delete'), $url, $options);
            };
        }
    }
}