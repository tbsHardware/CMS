<?php

namespace app\modules\posts\models;

use Yii;

/**
 * This is the model class for table "posts_post".
 *
 * @property integer $id
 * @property string $post_alias
 * @property string $post_title
 * @property string $post_description
 * @property string $post_content
 * @property string $post_status
 * @property string $post_type
 * @property integer $post_parent
 * @property integer $user_author
 * @property integer $post_date
 * @property integer $created_at
 * @property integer $updated_at
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts_post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_title', 'post_description', 'post_content'], 'string'],
            [['post_parent', 'user_author', 'post_date', 'created_at', 'updated_at'], 'integer'],
            [['user_author', 'post_date', 'created_at', 'updated_at'], 'required'],
            [['post_alias'], 'string', 'max' => 120],
            [['post_status', 'post_type'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_alias' => 'Post Alias',
            'post_title' => 'Post Title',
            'post_description' => 'Post Description',
            'post_content' => 'Post Content',
            'post_status' => 'Post Status',
            'post_type' => 'Post Type',
            'post_parent' => 'Post Parent',
            'user_author' => 'User Author',
            'post_date' => 'Post Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAuthor()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'user_author']);
    }
}
