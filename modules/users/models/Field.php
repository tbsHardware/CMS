<?php

namespace app\modules\users\models;

use Yii;

/**
 * This is the model class for table "users_field".
 *
 * @property integer $id
 * @property string $alias
 * @property string $title
 * @property integer $visible
 * @property integer $position
 * @property string $field_type
 * @property integer $field_size_min
 * @property integer $field_size_max
 * @property integer $required
 * @property string $default
 * @property string $range
 * @property string $other_validator
 *
 * @property UserField[] $userFields
 */
class Field extends \yii\db\ActiveRecord
{
    const VISIBLE_ALL = 3;
    const VISIBLE_REGISTER_USER = 2;
    const VISIBLE_ONLY_OWNER = 1;
    const VISIBLE_NO = 0;

    const REQUIRED_NO = 0;
    const REQUIRED_YES = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_field';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias', 'title'], 'required'],
            [['visible', 'position', 'field_size_min', 'field_size_max', 'required'], 'integer'],
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
            'visible' => 'Visible',
            'position' => 'Position',
            'field_type' => 'Field Type',
            'field_size_min' => 'Field Size Min',
            'field_size_max' => 'Field Size Max',
            'required' => 'Required',
            'default' => 'Default',
            'range' => 'Range',
            'other_validator' => 'Other Validator',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFields()
    {
        return $this->hasMany(UserField::className(), ['field_id' => 'id']);
    }
}
