<?php
/**
 * Created by PhpStorm.
 * User: yujt2<yujt2@lenovo.com>
 * Date: 18-10-18
 * Time: 下午2:46
 */
namespace app\controllers;

use yii\web\Controller;

class BaseController extends Controller
{
    public function init(){
        $response = \Yii::$app->response;
        $response->format = \Yii\web\Response::FORMAT_RAW;
        $response->headers->add('Content-Type', 'application/json');
        $this->enableCsrfValidation = false;
    }
}

