<?php

namespace app\modules\post\models;

use Yii;
use yii\db\ActiveRecord;
use app\modules\post\models\query\PostQuery;

/**
 * This is the model class for table "post_post".
 *
 * @property integer $id
 * @property string $path
 * @property string $title
 * @property string $description
 * @property string $content
 * @property integer $date
 * @property string $status
 * @property string $comment_status
 * @property integer $page_id
 * @property integer $author_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Page $page
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
            [['path', 'title', 'date', 'author_id', 'created_at', 'updated_at'], 'required'],
            [['title', 'description', 'content'], 'string'],
            [['date', 'page_id', 'author_id', 'created_at', 'updated_at'], 'integer'],
            [['path'], 'string', 'max' => 120],
            [['status', 'comment_status'], 'string', 'max' => 20],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => Page::className(), 'targetAttribute' => ['page_id' => 'id']],
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
            'description' => 'Description',
            'content' => 'Content',
            'date' => 'Date',
            'status' => 'Status',
            'comment_status' => 'Comment Status',
            'page_id' => 'Page ID',
            'author_id' => 'Author ID',
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
