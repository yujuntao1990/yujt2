<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clubs_applicants".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $club_id
 * @property string $created_at
 * @property string $updated_at
 */
class ClubsApplicants extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clubs_applicants';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'club_id'], 'required'],
            [['user_id', 'club_id'], 'integer'],
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
            'club_id' => 'Club ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
