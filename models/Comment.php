<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property string $comment
 * @property integer $user_id
 * @property integer $activity_id
 * @property string $status
 * @property string $created_at
 */
class Comment extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment', 'user_id', 'activity_id'], 'required'],
            [['user_id', 'activity_id'], 'integer'],
            [['comment', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comment' => 'Comment',
            'user_id' => 'User ID',
            'activity_id' => 'Activity ID',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    public function getUsers()
    {
        return $this->hasOne(Users::className(),['id'=>'user_id']);
    }
}
