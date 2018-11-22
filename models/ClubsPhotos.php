<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clubs_photos".
 *
 * @property integer $id
 * @property string $clubs_photo_url
 * @property integer $club_id
 * @property string $created_at
 * @property string $updated_at
 */
class ClubsPhotos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clubs_photos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['club_id', 'created_at', 'updated_at'], 'required'],
            [['club_id'], 'integer'],
            [['clubs_photo_url', 'created_at', 'updated_at'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clubs_photo_url' => 'Clubs Photo Url',
            'club_id' => 'Club ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
