<?php

namespace app\modules\post\models;

use Yii;
use yii\db\ActiveRecord;
use app\modules\post\models\query\MenuQuery;

/**
 * This is the model class for table "post_menu".
 *
 * @property integer $id
 * @property string $type
 * @property integer $link_id
 * @property string $label
 * @property integer $parent_id
 * @property integer $position
 * @property string $visible
 *
 * @property Menu $parent
 * @property Menu[] $children
 */
class Menu extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link_id'], 'required'],
            [['link_id', 'parent_id', 'position'], 'integer'],
            [['type'], 'string', 'max' => 20],
            [['label', 'visible'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 
                'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'link_id' => 'Link ID',
            'label' => 'Label',
            'parent_id' => 'Parent ID',
            'position' => 'Position',
            'visible' => 'Visible',
        ];
    }
    
    public function getUrl()
    {
        return $this->link->getUrl();
    }

    public function getTitle()
    {
        return $this->label ?: $this->link->title;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        if ($this->type === 'page') {
            $class = \app\modules\post\models\Page::className();   
        } elseif ($this->type === 'post') {
            $class = \app\modules\post\models\Post::className();
        } else {
            return false;
        }
        
        return $this->hasOne($class, ['id' => 'link_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Menu::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Menu::className(), ['parent_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\modules\post\models\query\MenuQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MenuQuery(get_called_class());
    }
}
