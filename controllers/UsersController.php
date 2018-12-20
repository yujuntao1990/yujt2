<?php

namespace app\controllers;

use app\models\Activities;
use app\models\Clubs;
use app\models\Sponsors;
use Yii;
use app\models\Users;
use app\models\UsersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends BaseController
{
    /**
     * @inheritdoc
     */

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $data=Users::find()->where(['id'=>$id])->asArray()->one();
        if ($data){
            unset($data['password']);
            return json_encode(['code' => 200, 'message' => "获取数据成功", 'data' => $data]);
        }
        return json_encode(['code'=>500,'message'=>'空数据']);
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();
        $post = Yii::$app->request->post();
        //var_dump(empty($post));exit();
        if (!empty($post)) {
            $data=Users::find()->where(['telephone'=>$post['telephone']])->one();
            if ($data){
                return json_encode(['code'=>300,'message'=>'手机号已存在']);
            }
            $model->telephone=$post['telephone'];
            $model->username=$post['username'];
            if (empty($post['password'])){
                return json_encode(['code'=>500,'message'=>'密码不能为空']);
            }
            $model->password=md5($post['password']);
            if (isset($post['identification'])){
                $model->identification=$post['identification'];
            }
            if ($model->save()){
                return json_encode(['code'=>200,'message'=>'添加成功']);
            }
//            return $this->redirect(['view', 'id' => $model->id]);
        }
        return json_encode(['code'=>500,'message'=>'添加失败','data'=>$model->getErrors()]);
//            return $this->render('create', [
//                'model' => $model,
//            ]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $post = Yii::$app->request->post();

        if (!empty($post)) {
            $model->username=$post['username'];
            $model->card=$post['card'];
            if ($model->save())
            {
                return json_encode(['code'=>200,'message'=>'修改成功']);
            }
//            return $this->redirect(['view', 'id' => $model->id]);
        }
            return json_encode(['code'=>500,'message'=>'修改失败','data'=>$model->getErrors()]);
//            return $this->render('create', [
//                'model' => $model,
//            ]);
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionLogin()
    {
        $post=Yii::$app->request->post();

        $data=Users::find()->where(['telephone'=>$post['telephone'],'password'=>md5($post['password'])])->asArray()->one();
        //var_dump($data->createCommand()->getRawSql());
        if (!empty($data)){
            unset($data['password']);
            return json_encode(['code'=>200,'message'=>'登录成功','data'=>$data]);
        }
        return json_encode(['code'=>500,'message'=>'登录失败']);
    }

    public function actionHead($id)
    {
        $model=$this->findModel($id);
        if ($model){
            if (isset($_FILES['head_url'])){
                move_uploaded_file($_FILES['head_url']["tmp_name"], "head/" . time() . $_FILES['head_url']["name"]);
                $model->head_url=time(). $_FILES['head_url']["name"];
                if ($model->save()){
                    return json_encode(['code'=>200,'message'=>'添加成功']);
                }
            }
        }
        return json_encode(['code'=>500,'message'=>'修改失败','data'=>$model->getErrors()]);
    }

    public function actionUsername($id)
    {
        $model=$this->findModel($id);
        if ($model){
            $post=Yii::$app->request->post();
            if (!empty($post)){
                $model->username=$post['username'];
                if ($model->save()){
                    return json_encode(['code'=>200,'message'=>'修改成功']);
                }
            }
        }
        return json_encode(['code'=>500,'message'=>'修改失败','data'=>$model->getErrors()]);
    }

    public function actionPassword($id)
    {
        $model=$this->findModel($id);
        if ($model){
            $post=Yii::$app->request->post();
            if (!empty($post['password'])){
                $model->password=md5($post['password']);
                if ($model->save()){
                    return json_encode(['code'=>200,'message'=>'修改成功']);
                }
            }
        }
        return json_encode(['code'=>500,'message'=>'修改失败','data'=>$model->getErrors()]);
    }

    public function actionTel($id)
    {
        $model=$this->findModel($id);
        if ($model){
            $post=Yii::$app->request->post();
            if (!empty($post)){
                $a=Users::find()->where(['telephone'=>$post['telephone']])->one();
                if ($a){
                    return json_encode(['code'=>300,'message'=>'手机号已存在']);
                }
                $model->telephone=$post['telephone'];
                if ($model->save()){
                    return json_encode(['code'=>200,'message'=>'修改成功']);
                }
            }
        }
        return json_encode(['code'=>500,'message'=>'修改失败','data'=>$model->getErrors()]);
    }

    public function actionGettel($id)
    {
        $model=$this->findModel($id);
        if ($model){
            return json_encode(['code'=>200,'message'=>'获取成功','data'=>['tel'=>$model->telephone]]);
        }
        return json_encode(['code'=>500,'message'=>'空数据']);
    }

    public function actionBrank($id)
    {
        $model=$this->findModel($id);
        if ($model){
            $post=Yii::$app->request->post();
            if (!empty($post)){
                $model->bank_card=$post['bank_card'];
                $model->truename=$post['truename'];
                $model->bank_deposit=$post['bank_deposit'];
                if ($model->save()){
                    return json_encode(['code'=>200,'message'=>'添加成功']);
                }
            }
        }
        return json_encode(['code'=>500,'message'=>'添加失败','data'=>$model->getErrors()]);
    }

    public function actionCreated($id)
    {
        $data=[];
        $data['Activities']=Activities::find()->where(['user_id'=>$id])->with('sports')->asArray()->all();
        $data['Clubs']=Clubs::find()->where(['user_id'=>$id])->with('sports')->asArray()->all();
        $data['Sponsors']=Sponsors::find()->where(['user_id'=>$id])->with('sports')->asArray()->all();
        return json_encode(['code'=>200,'message'=>'获取成功','data'=>$data]);
    }

    public function actionIsCreated($id)
    {
        $activity_created=Clubs::findAll(['user_id'=>$id]);
        $sponsors_created=Sponsors::findAll(['user_id'=>$id]);
        if ($activity_created&&$sponsors_created)
        {
            return json_encode(['code'=>200,'message'=>'用户已创建俱乐部和赞助商']);
        }elseif ($activity_created)
        {
            return json_encode(['code'=>201,'message'=>'用户已创建俱乐部']);
        }elseif ($sponsors_created)
        {
            return json_encode(['code'=>202,'message'=>'用户已创建赞助商']);
        }else
        {
            return json_encode(['code'=>500,'message'=>'用户啥都没创建']);
        }
    }
}
