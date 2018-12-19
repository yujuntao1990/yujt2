<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sports".
 *
 * @property integer $id
 * @property integer $sport_content
 */
class Sports extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sports';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sport_content'], 'required'],
            [['sport_content'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sport_content' => 'sport_content',
        ];
    }
}
