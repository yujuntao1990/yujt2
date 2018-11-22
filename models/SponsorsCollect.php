<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sponsors_collect".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $sponsors_id
 * @property string $created_at
 * @property string $updated_at
 */
class SponsorsCollect extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sponsors_collect';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'sponsors_id'], 'required'],
            [['user_id', 'sponsors_id'], 'integer'],
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
            'sponsors_id' => 'Sponsors ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
