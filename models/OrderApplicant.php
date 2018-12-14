<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_applicant".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $applicant_id
 * @property integer $activity_id
 */
class OrderApplicant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_applicant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'applicant_id','activity_id'], 'required'],
            [['order_id', 'applicant_id','activity_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'applicant_id' => 'Applicant ID',
            'activity_id' => 'activity_id'
        ];
    }
}
