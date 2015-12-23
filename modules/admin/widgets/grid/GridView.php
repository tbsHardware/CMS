<?php

namespace app\modules\admin\widgets\grid;

use Yii;
use app\modules\admin\widgets\grid\assets\GridAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class GridView extends \yii\grid\GridView
{
    public $tableOptions = ['class' => 'table table-striped table-bordered table-hover dataTable no-footer'];

    public $enableSizer = true;

    public $sizerItems = [
        10 => 10,
        25 => 25,
        50 => 50,
    ];

    public $sizerOptions = [
        'class' => 'form-control input-sm input-xsmall input-inline',
    ];

    public $headerButtons;

    public $layout = "
        <div class='dataTables_wrapper no-footer'>
            <div class='row'>
                <div class='col-md-6 col-sm-6'>{buttons}</div>
                <div class='col-md-6 col-sm-6'>
                    <div class='dataTables_filter dataTables_length'>{sizer}</div>
                </div>
            </div>
            <div class='table-scrollable'>{items}</div>
            <div class='row'>
                <div class='col-md-5 col-sm-5'>
                    <div class='dataTables_info'>{summary}</div>
                </div>
                <div class='col-md-7 col-sm-7'>
                    <div class='dataTables_paginate'>{pager}</div>
                </div>
            </div>
        </div>
    ";

    public function init() {
        parent::init();

        if (!isset($this->sizerOptions['id'])) {
            $this->sizerOptions['id'] = $this->id . '_pagesize';
        }
        $this->filterSelector = '#' . $this->sizerOptions['id'];

        $pageSize = Yii::$app->request->getQueryParam($this->sizerOptions['id']);
        if ($pageSize && isset($this->sizerItems[$pageSize])) {
            $this->dataProvider->setPagination(['pageSize' => $pageSize]);
        }

        $view = Yii::$app->getView();
        GridAsset::register($view);
    }

    public function renderSection($name)
    {
        switch ($name) {
            case '{summary}':
                return $this->renderSummary();
            case '{items}':
                return $this->renderItems();
            case '{pager}':
                return $this->renderPager();
            case '{sorter}':
                return $this->renderSorter();
            case '{buttons}':
                return $this->renderButtons();
            case '{sizer}':
                return $this->renderSizer();
            default:
                return false;
        }
    }

    public function renderButtons()
    {
        $buttons = '';

        if ($this->headerButtons) {

            $buttons .= Html::beginTag('div', ['class' => 'btn-group']);

            foreach ($this->headerButtons as $button) {
                if (!isset($button['url']) || !isset($button['label'])) {
                    throw new \yii\base\InvalidConfigException("The 'url' and 'label' options is required.");
                }

                $options = ArrayHelper::getValue($button, 'options');

                $buttons .= Html::a($button['label'], $button['url'], $options);
            }
            $buttons .= Html::endTag('div');
        }

        return $buttons;
    }

    public function renderSizer()
    {
        if ($this->enableSizer) {

            $name = $this->sizerOptions['id'];
            $value = Yii::$app->request->getQueryParam($name);
            $select = Html::dropDownList($name, $value, $this->sizerItems, $this->sizerOptions);

            return Html::tag('label', Yii::t('admin', 'Show {0}', $select));
        }

        return '';
    }
}