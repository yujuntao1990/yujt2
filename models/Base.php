<?php
/**
 * Created by PhpStorm.
 * User: yujt2<yujt2@lenovo.com>
 * Date: 18-10-16
 * Time: 下午2:36
 */
namespace app\models;

use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Base extends ActiveRecord{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }
}