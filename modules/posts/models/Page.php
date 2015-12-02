<?php

namespace app\modules\posts\models;

use app\modules\posts\models\query\PageQuery;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "posts_page".
 *
 * @property integer $id
 * @property string $page_path
 * @property string $page_title
 * @property string $page_content
 * @property integer $page_parent
 * @property string $page_template
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Post[] $posts
 */
class Page extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts_page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_path', 'page_title', 'created_at', 'updated_at'], 'required'],
            [['page_title', 'page_content'], 'string'],
            [['page_parent', 'created_at', 'updated_at'], 'integer'],
            [['page_path'], 'string', 'max' => 120],
            [['page_template'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page_path' => 'Page Path',
            'page_title' => 'Page Title',
            'page_content' => 'Page Content',
            'page_parent' => 'Page Parent',
            'page_template' => 'Page Template',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostsPosts()
    {
        return $this->hasMany(Post::className(), ['page_id' => 'id']);
    }

    public static function find()
    {
        return new PageQuery(get_called_class());
    }
}
