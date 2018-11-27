<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "activities_photos".
 *
 * @property integer $id
 * @property string $activity_photo
 * @property integer $activity_id
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 */
class ActivitiesPhotos extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activities_photos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_photo', 'activity_id', 'user_id'], 'required'],
            [['activity_id', 'user_id'], 'integer'],
            [['activity_photo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_photo' => 'Activity Photo',
            'activity_id' => 'Activity ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
