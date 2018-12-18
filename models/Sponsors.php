<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sponsors".
 *
 * @property integer $id
 * @property string $sponsors_name
 * @property string $sponsors_about
 * @property string $contact
 * @property string $photo
 * @property string $sponsors_activity
 * @property integer $sports_id
 * @property integer $life_id
 * @property integer $money
 * @property string $begin_time
 * @property string $end_time
 * @property integer $people_num
 * @property string $address
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 */
class Sponsors extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sponsors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sponsors_name', 'sponsors_about', 'contact', 'sponsors_activity','life_id', 'sports_id', 'money', 'begin_time', 'end_time', 'people_num', 'address', 'user_id'], 'required'],
            [['sports_id', 'life_id','money', 'people_num', 'user_id'], 'integer'],
            [['sponsors_name', 'sponsors_about', 'contact', 'photo', 'sponsors_activity', 'begin_time', 'end_time', 'address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sponsors_name' => 'Sponsors Name',
            'sponsors_about' => 'Sponsors About',
            'contact' => 'Contact',
            'photo' => 'Photo',
            'sponsors_activity' => 'Sponsors Activity',
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
}
