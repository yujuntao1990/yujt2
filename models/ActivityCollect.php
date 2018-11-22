<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "activity_collect".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $activity_id
 * @property string $created_at
 * @property string $updated_at
 */
class ActivityCollect extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_collect';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'activity_id'], 'required'],
            [['user_id', 'activity_id'], 'integer'],
            [['created_at', 'updated_at'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'activity_id' => 'Activity ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
