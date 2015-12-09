<?php

namespace app\modules\post\models;

use app\modules\post\models\query\PostQuery;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "post_post".
 *
 * @property integer $id
 * @property string $post_path
 * @property string $post_title
 * @property string $post_description
 * @property string $post_content
 * @property integer $post_date
 * @property string $post_status
 * @property string $comment_status
 * @property integer $page_id
 * @property integer $user_author
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Page[] $page
 * @property $author
 */
class Post extends ActiveRecord
{
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_USER_ONLY = 'user_only';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_post}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_path', 'post_title', 'post_date', 'user_author', 'created_at', 'updated_at'], 'required'],
            [['post_title', 'post_description', 'post_content'], 'string'],
            [['post_date', 'page_id', 'user_author', 'created_at', 'updated_at'], 'integer'],
            [['post_path'], 'string', 'max' => 120],
            [['post_status', 'comment_status'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_path' => 'Post Path',
            'post_title' => 'Post Title',
            'post_description' => 'Post Description',
            'post_content' => 'Post Content',
            'post_date' => 'Post Date',
            'post_status' => 'Post Status',
            'comment_status' => 'Comment Status',
            'page_id' => 'Page ID',
            'user_author' => 'User Author',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'user_author']);
    }

    public static function find()
    {
        return new PostQuery(get_called_class());
    }
}
