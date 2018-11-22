<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sponsors_commodity".
 *
 * @property integer $id
 * @property string $commodity_name
 * @property string $commodity_price
 * @property string $commodity_url
 * @property string $sponsors_id
 * @property string $created_at
 * @property string $updated_at
 */
class SponsorsCommodity extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sponsors_commodity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['commodity_name', 'commodity_price', 'commodity_url', 'sponsors_id'], 'required'],
            [['commodity_name', 'commodity_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'commodity_name' => 'Commodity Name',
            'commodity_price' => 'Commodity Price',
            'commodity_url' => 'Commodity Url',
            'sponsors_id' => 'Sponsors ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
