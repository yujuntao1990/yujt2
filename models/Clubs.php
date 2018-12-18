<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clubs".
 *
 * @property integer $id
 * @property string $club_name
 * @property string $club_about
 * @property string $contact
 * @property string $photo
 * @property string $club_activity
 * @property string $life_id
 * @property integer $sports_id
 * @property integer $money
 * @property string $begin_time
 * @property string $end_time
 * @property integer $people_num
 * @property string $address
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 */
class Clubs extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clubs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['club_name', 'club_about', 'contact','user_id','life_id'], 'required'],
            [['user_id','life_id'], 'integer'],
            [['club_name', 'club_about', 'contact','photo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'club_name' => 'Club Name',
            'club_about' => 'Club About',
            'contact' => 'contact',
            'photo' => 'Photo',
            'club_activity' => 'Club Activity',
            'life_id' => 'life_id',
            'sports_id' => 'Sports ID',
            'money' => 'Money',
            'begin_time' => 'Begin Time',
            'end_time' => 'End Time',
            'people_num' => 'People Num',
            'address' => 'Address',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getUsers()
    {
        return $this->hasOne(Users::className(),['id'=>'user_id']);
    }

    public function getSports()
    {
        return $this->hasOne(Sports::className(),['id'=>'sports_id']);
    }

    public function getLifes()
    {
        return $this->hasOne(Lifes::className(),['id'=>'life_id']);
    }
}
