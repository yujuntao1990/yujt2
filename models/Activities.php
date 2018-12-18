<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "activities".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $cate_id
 * @property string $reg_time_start
 * @property string $reg_time_end
 * @property string $people_num
 * @property string $address
 * @property string $entry_fee
 * @property string $life_id
 * @property integer $sports_id
 * @property string $contact
 * @property integer $user_id
 * @property integer $photo_url
 * @property string $sum_up
 * @property string $created_at
 * @property string $updated_at
 */
class Activities extends Base
{
    public $applicants;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'cate_id','reg_time_start', 'reg_time_end', 'sports_id', 'life_id','contact', 'user_id','people_num','address','entry_fee'], 'required'],
            [['cate_id', 'sports_id', 'user_id','life_id'], 'integer'],
            [['title', 'content', 'reg_time_start', 'reg_time_end','contact','people_num','address'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'cate_id' => 'Cate ID',
            'reg_time_start' => 'Reg Time Start',
            'reg_time_end' => 'Reg Time End',
            'people_num' => 'people_num',
            'address' => 'address',
            'entry_fee' => 'Entry Fee',
            'life_id' => 'life_id',
            'sports_id' => 'Sports ID',
            'contact' => 'Contact',
            'user_id' => 'User ID',
            'photo_url' => 'photo_url',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'sum_up' => 'sum_up'
        ];
    }

    public static function getCate()
    {
        return ArrayHelper::map(Cate::find()->all(),'id','content');
    }

    public static function getSport()
    {
        return ArrayHelper::map(Sports::find()->all(),'id','content');
    }

    public function getCates()
    {
        return $this->hasOne(Cate::className(),['id'=>'cate_id']);
    }

    public function getSports()
    {
        return $this->hasOne(Sports::className(),['id'=>'sports_id']);
    }

    public function getUsers()
    {
        return $this->hasOne(Users::className(),['id'=>'user_id']);
    }

    public function getLifes()
    {
        return $this->hasOne(Lifes::className(),['id'=>'life_id']);
    }
}
