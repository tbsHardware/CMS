<?php
namespace app\modules\admin\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use wfcreations\simplelineicons\SLI;

class PageSidebar extends Widget
{
    public $sidebarWrapperOptions = [
        'class' => 'page-sidebar-wrapper',
    ];

    public $sidebarOptions = [
        'class' => 'page-sidebar navbar-collapse collapse',
    ];

    public $options = [
        'class' => 'page-sidebar-menu page-header-fixed scroller',
        'data-keep-expanded' => 'false',
        'data-auto-scroll' => 'true',
        'data-slide-speed' => 200,
    ];

    public $itemOptions = [
        'class' => 'nav-item',
    ];

    public $linkOptions = [
        'class' => 'nav-link',
    ];

    public $dropDownCaretOptions = [
        'class' => 'arrow',
    ];

    public $route;

    protected $items = [];

    public function init()
    {
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }

        $admin = Yii::$app->getModule('admin');
        $this->items = ArrayHelper::merge($this->items, $admin->controlMenu);

        $modules = $admin->managedModules;
        foreach ($modules as $id => $module) {
            $this->items = ArrayHelper::merge($this->items, $module->controlMenu);
        }
    }

    public function run()
    {
        echo Html::beginTag('div', $this->sidebarWrapperOptions);
        echo Html::beginTag('div', $this->sidebarOptions);
        echo $this->renderItems();
        echo Html::endTag('div');
        echo Html::endTag('div');
    }

    public function renderItems()
    {
        $items = [];
        foreach ($this->items as $item) {
            if (isset($item['role']) && !Yii::$app->user->can($item['role'])) {
                continue;
            }
            $items[] = $this->renderItem($item);
        }

        return Html::tag('ul', implode("\n", $items), $this->options);
    }

    public function renderItem($item)
    {
        if (is_string($item)) {
            return $item;
        }
        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }

        $label = isset($item['icon']) && $item['icon'] ? SLI::icon($item['icon']) : '';
        $label .= Html::tag('span', Html::encode($item['label']), ['class' => 'title']);

        $items = ArrayHelper::getValue($item, 'items');
        $linkOptions = $this->linkOptions;
        $itemOptions = $this->itemOptions;
        $dropDownCaretOptions = $this->dropDownCaretOptions;

        $active = $this->isItemActive($item);

        if ($items) {
            Html::addCssClass($linkOptions, 'nav-toggle');
            if (is_array($items)) {
                $items = $this->isChildActive($items, $active);
                $items = $this->renderChildren($items);
                if ($active) {
                    Html::addCssClass($dropDownCaretOptions, 'open');
                    Html::addCssClass($itemOptions, 'open');
                }
            }
            $label .= Html::tag('span', '', $dropDownCaretOptions);
        }
        if ($active) {
            Html::addCssClass($itemOptions, 'active');
            $label .= Html::tag('span', '', ['class' => 'selected']);
        }

        return Html::tag('li', Html::a($label, $this->getUrl($item), $linkOptions) . $items, $itemOptions);
    }

    public function renderChildren($items)
    {
        $renderItems = [];
        foreach ($items as $item) {
            if (isset($item['role']) && !Yii::$app->user->can($item['role'])) {
                continue;
            }
            $itemOptions = $this->itemOptions;
            if ($this->isItemActive($item)) {
                Html::addCssClass($itemOptions, 'active');
            }
            $label = Html::tag('span', Html::encode($item['label']), ['class' => 'title']);
            $link = Html::a($label, $this->getUrl($item), $this->linkOptions);

            $renderItems[] = Html::tag('li', $link, $itemOptions);
        }

        return Html::tag('ul', implode("\n", $renderItems), ['class' => 'sub-menu']);
    }

    protected function getUrl($item)
    {
        $action = ArrayHelper::getValue($item, 'action');

        return $action ? ['/admin/' . $action] : '#';
    }

    protected function isChildActive($items, &$active)
    {
        foreach ($items as $i => $child) {
            if (ArrayHelper::remove($items[$i], 'active', false) || $this->isItemActive($child)) {
                Html::addCssClass($items[$i]['options'], 'active');
                $active = true;
            }
        }

        return $items;
    }

    protected function isItemActive($item)
    {
        $url = $this->getUrl($item);
        if (is_array($url) && isset($url[0])) {
            $route = $url[0];
            if ($route[0] !== '/' && Yii::$app->controller) {
                $route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
            }
            if (ltrim($route, '/') !== $this->route) {
                return false;
            }
            unset($url['#']);
            if (count($url) > 1) {
                $params = $url;
                unset($params[0]);
                foreach ($params as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                        return false;
                    }
                }
            }

            return true;
        }

        return false;
    }
}