<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property string $order_number
 * @property integer $no_status
 * @property integer $activity_id
 * @property integer $group_id
 * @property integer $already_arrived
 * @property integer $money
 * @property integer $user_id
 * @property string $created_at
 * @property string $updated_at
 */
class Orders extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_number', 'no_status', 'activity_id', 'group_id', 'already_arrived'], 'required'],
            [['no_status', 'activity_id', 'group_id', 'already_arrived', 'money', 'user_id'], 'integer'],
            [['order_number', 'created_at', 'updated_at'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_number' => 'Order Number',
            'no_status' => 'No Status',
            'activity_id' => 'Activity ID',
            'group_id' => 'Group ID',
            'already_arrived' => 'Already Arrived',
            'money' => 'Money',
            'user_id' => 'user_id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
