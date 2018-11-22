<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $username
 * @property string $telephone
 * @property integer $club_id
 * @property string $card
 * @property string $password
 * @property string $head_url
 * @property string $identification
 * @property string $create_at
 * @property string $update_at
 */
class Users extends Base
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['telephone', 'password','username'], 'required'],
            [['club_id'], 'integer'],
            [['username', 'telephone', 'card', 'password', 'head_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'telephone' => 'Telephone',
            'club_id' => 'Club ID',
            'card' => 'Card',
            'password' => 'Password',
            'head_url' => 'Head Url',
            'identification' => 'identification',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }
}
