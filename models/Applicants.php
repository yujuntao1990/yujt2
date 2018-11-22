<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "applicants".
 *
 * @property integer $id
 * @property string $username
 * @property string $tel
 * @property string $card
 * @property integer $activity_id
 * @property integer $user_id
 */
class Applicants extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'applicants';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'tel', 'card', 'activity_id','user_id'], 'required'],
            [['activity_id'], 'integer'],
            [['username', 'tel', 'card'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'tel' => 'Tel',
            'card' => 'Card',
            'activity_id' => 'Activity ID',
            'user_id' => 'user_id'
        ];
    }

    public function getActivity()
    {
        return $this->hasMany(Activities::className(),['id'=>'activity_id']);
    }
}
