<?php

namespace app\modules\post\models;

use Yii;
use yii\db\ActiveRecord;
use app\modules\post\models\query\PageQuery;

/**
 * This is the model class for table "post_page".
 *
 * @property integer $id
 * @property string $path
 * @property string $title
 * @property string $content
 * @property integer $page_parent
 * @property string $template
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Page $parent
 * @property Page[] $children
 * @property Post[] $posts
 */
class Page extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['path', 'title', 'created_at', 'updated_at'], 'required'],
            [['title', 'content'], 'string'],
            [['page_parent', 'created_at', 'updated_at'], 'integer'],
            [['path'], 'string', 'max' => 120],
            [['template'], 'string', 'max' => 20],
            [['page_parent'], 'exist', 'skipOnError' => true, 'targetClass' => Page::className(), 'targetAttribute' => ['page_parent' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Path',
            'title' => 'Title',
            'content' => 'Content',
            'page_parent' => 'Page Parent',
            'template' => 'Template',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_parent']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Page::className(), ['page_parent' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['page_id' => 'id']);
    }

    public static function find()
    {
        return new PageQuery(get_called_class());
    }
}
