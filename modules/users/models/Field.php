<?php

namespace app\modules\users\models;

use app\modules\users\models\query\FieldQuery;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "users_field".
 *
 * @property integer $id
 * @property string $alias
 * @property string $title
 * @property string $field_type
 * @property integer $field_size_min
 * @property integer $field_size_max
 * @property integer $required
 * @property string $default
 * @property string $range
 * @property string $other_validator
 */
class Field extends ActiveRecord
{
    const VISIBLE_ALL = 3;
    const VISIBLE_REGISTER_USER = 2;
    const VISIBLE_ONLY_OWNER = 1;
    const VISIBLE_NO = 0;

    const REQUIRED_NO = 0;
    const REQUIRED_YES_SHOW_REG = 1;
    const REQUIRED_NO_SHOW_REG = 2;
    const REQUIRED_YES_NOT_SHOW_REG = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users_field}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'title'], 'required'],
            [['field_size_min', 'field_size_max', 'required'], 'integer'],
            [['alias', 'field_type'], 'string', 'max' => 32],
            [['title', 'default', 'range', 'other_validator'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => 'Alias',
            'title' => 'Title',
            'field_type' => 'Field Type',
            'field_size_min' => 'Field Size Min',
            'field_size_max' => 'Field Size Max',
            'required' => 'Required',
            'default' => 'Default',
            'range' => 'Range',
            'other_validator' => 'Other Validator',
        ];
    }

    public static function find()
    {
        return new FieldQuery(get_called_class());
    }
}
