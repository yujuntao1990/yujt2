<?php

namespace app\controllers;

use app\models\Base;
use app\models\SponsorsCollect;
use app\models\SponsorsCommodity;
use app\models\Users;
use Yii;
use app\models\Sponsors;
use app\models\SponsorsSearch;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;


/**
 * SponsorsController implements the CRUD actions for Sponsors model.
 */
class SponsorsController extends BaseController
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
     * Lists all Sponsors models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SponsorsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sponsors model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $data=$this->findModel($id);
        $data=ArrayHelper::toArray($data);
        //var_dump($data);exit();
        if (!empty($data)){
            $user=Users::findOne($data['user_id']);
            if ($user){
                $data['username']=$user->username;
                $data['telephone']=$user->telephone;
                $data['user_head']=$user->head_url;
                $commodity=SponsorsCommodity::find()->where(['sponsors_id'=>$id])->asArray()->all();
                if (!empty($commodity)){
                    foreach ($commodity as $key => $value){
                        $data['commodity'][$key]=$commodity[$key];
                    }
                }
                return json_encode(['code'=>200,'message'=>'获取数据成功','data'=>$data]);
            }
        }
        return json_encode(['code'=>500,'message'=>'空数据']);
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
    }

    /**
     * Creates a new Sponsors model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sponsors();
        $post = Yii::$app->request->post();

        $transaction = Yii::$app->db->beginTransaction();
        try{

            if (!empty($post)) {
                $model->sponsors_name = $post['sponsors_name'];
                $model->sponsors_about = $post['sponsors_about'];
                $model->contact = $post['contact'];
                $model->sponsors_activity = $post['sponsors_activity'];
                $model->life_id = $post['life_id'];
                $model->sports_id = $post['sports_id'];
                $model->money = $post['money'];
                $model->begin_time = $post['begin_time'];
                $model->end_time = $post['end_time'];
                $model->people_num = $post['people_num'];
                $model->address = $post['address'];
                $model->user_id = $post['user_id'];


                if (!$model->validate()) {
                    throw new Exception($model->getErrors());
                }
                if (isset($_FILES['photo'])){
                    $model->photo = time() . $_FILES['photo']["name"];
                }
                if (!$model->save(false)) {
                    throw new Exception('添加失败');
                }
                if (isset($_FILES['photo'])) {
                    move_uploaded_file($_FILES['photo']["tmp_name"], "sponsors/" . time() . $_FILES['photo']["name"]);
                    //echo "文件存储在: " . "upload/" . $file["name"];
                }
                if (isset($_FILES['SponsorsCommodity'])) {
                    foreach ($_FILES['SponsorsCommodity']['name'] as $key => $value){
                        $com = new SponsorsCommodity();
                        $com->commodity_name=$post['commodity_name'][$key];
                        $com->commodity_price=$post['commodity_price'][$key];
                        $com->sponsors_id=$model->id;
                        move_uploaded_file($_FILES['SponsorsCommodity']["tmp_name"][$key], "sponsors/" . time() . $_FILES['SponsorsCommodity']["name"][$key]);
                        $com->commodity_url = time() . $_FILES['SponsorsCommodity']["name"][$key];
                        if (!$com->validate()){
                            throw new Exception($com->getfErrors());
                        }
                        if (!$com->save(false)){
                            throw new Exception('添加失败');
                        }

                    }
                }
                $transaction->commit();
                return json_encode(['code' => 200, 'message' => '添加成功']);
            }
        }catch (\yii\base\Exception $e){
            $transaction->rollBack();
            return json_encode(['code'=>500,'message'=>'添加失败','data'=>$e->getMessage()]);
        }


//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
    }

    /**
     * Updates an existing Sponsors model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Sponsors model.
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
     * Finds the Sponsors model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sponsors the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sponsors::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionList()
    {
        $data=Sponsors::find()->with('sports')->asArray()->all();
        //var_dump($data->createCommand()->getRawSql());exit();

        if (!empty($data)){
            foreach ($data as $key => $value){
                $data[$key]['telephone']=Users::findOne($data[$key]['user_id'])->telephone;
                $data[$key]['username']=Users::findOne($data[$key]['user_id'])->username;
            }
            return json_encode(['code'=>200,'message'=>'获取数据成功','data'=>$data]);
        }
        return json_encode(['code'=>500,'message'=>'空数据']);
    }

    public function actionAdd()
    {
        $model=new SponsorsCollect();
        $post = Yii::$app->request->post();
        //var_dump($post['user_id']);exit();
        if (!empty($post)){
            $model->user_id=$post['user_id'];
            $model->sponsors_id=$post['sponsors_id'];
            if ($model->save()){
                return json_encode(['code'=>200,'message'=>'收藏成功']);
            }
        }
        return json_encode(['code'=>500,'message'=>'收藏失败','data'=>$model->getErrors()]);
    }

    public function actionDel()
    {
        $post = Yii::$app->request->post();
        if (!empty($post)){
            $model=SponsorsCollect::find()->where(['user_id'=>$post['user_id'],'sponsors_id'=>$post['sponsors_id']])->one();
            if ($model->delete()){
                return json_encode(['code'=>200,'message'=>'删除成功']);
            }
        }
        return json_encode(['code'=>500,'message'=>'删除失败']);
    }

    public function actionFollow($user_id)
    {
        $data=SponsorsCollect::find()->where(['user_id'=>$user_id])->select('user_id,sponsors_id')->asArray()->all();
        if ($data){
            foreach ($data as $key => $value){
                $clubs=Sponsors::find()->where(['id'=>$data[$key]['sponsors_id']])->with('sports')->asArray()->one();
                $data[$key]['sponsors_name']=$clubs['sponsors_name'];
                $data[$key]['sponsors_activity']=$clubs['sponsors_activity'];
                $data[$key]['sports']=$clubs['sports'];
            }
            return json_encode(['code'=>200,'message'=>'获取数据成功','data'=>$data]);
        }
        return json_encode(['code'=>500,'message'=>'空数据']);
    }
}
