<?php

namespace app\controllers;

use app\models\ClubsApplicants;
use app\models\ClubsPhotos;
use app\models\Users;
use Yii;
use app\models\Clubs;
use app\models\ClubsSearch;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * ClubsController implements the CRUD actions for Clubs model.
 */
class ClubsController extends BaseController
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
     * Lists all Clubs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClubsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Clubs model.
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
                $club_applicants=ClubsApplicants::find()->where(['club_id'=>$id])->asArray()->all();
                if ($club_applicants){
                    foreach ($club_applicants as $key => $value){
                        $user=Users::find()->where(['id'=>$club_applicants[$key]['user_id']])->asArray()->one();
                        $data['club_applicants'][$key]['username']=$user['username'];
                        $data['club_applicants'][$key]['user_head']=$user['head_url'];
                    }
                }
                $clubphoto=ClubsPhotos::find()->where(['club_id'=>$id])->asArray()->all();
                if ($clubphoto){
                    foreach ($clubphoto as $key => $value){
                        $data['clubphoto'][$key]=$clubphoto[$key];
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
     * Creates a new Clubs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Clubs();
        $post = Yii::$app->request->post();

        $transaction = Yii::$app->db->beginTransaction();
        try{
            if (!empty($post)) {
                $model->club_name = $post['club_name'];
                $model->club_about = $post['club_about'];
                $model->contact = $post['contact'];
//            $model->club_activity = $post['club_activity'];
//            $model->sports_id = $post['sports_id'];
//            $model->money = $post['money'];
//            $model->begin_time = $post['begin_time'];
//            $model->end_time = $post['end_time'];
//            $model->people_num = $post['people_num'];
//            $model->address = $post['address'];
                $model->user_id = $post['user_id'];

                if (!$model->validate()) {
                    throw new Exception($model->getErrors());
                }
                if (isset($_FILES['photo'])) {
                    $model->photo = time() . $_FILES['photo']["name"];
                }
                if (!$model->save(false)) {
                    throw new Exception('添加失败');
                }
                if (isset($_FILES['photo'])) {
                    move_uploaded_file($_FILES['photo']["tmp_name"], "clubs/" . time() . $_FILES['photo']["name"]);
                }
                //var_dump($_FILES['ClubPhoto']["name"]);exit();
                if (isset($_FILES['ClubPhoto'])){
                    foreach ($_FILES['ClubPhoto']['name']  as $key => $value){
                        $clubphoto=new ClubsPhotos();
                        $clubphoto->club_id=$model->id;
                        move_uploaded_file($_FILES['ClubPhoto']["tmp_name"][$key], "clubs/" . time() . $_FILES['ClubPhoto']["name"][$key]);
                        $clubphoto->clubs_photo_url=time().$_FILES['ClubPhoto']["name"][$key];
                        if (!$clubphoto->validate()){
                            throw new Exception($clubphoto->getErrors());
                        }
                        if (!$clubphoto->save(false)){
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
     * Updates an existing Clubs model.
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
     * Deletes an existing Clubs model.
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
     * Finds the Clubs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Clubs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Clubs::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionList()
    {
        $data=Clubs::find()->asArray()->all();
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
        $model=new ClubsApplicants();
        $post = Yii::$app->request->post();
        if (!empty($post)){
            $model->user_id=$post['user_id'];
            $model->club_id=$post['club_id'];
            if ($model->save()){
                return json_encode(['code'=>200,'message'=>'添加成功']);
            }
        }
        return json_encode(['code'=>500,'message'=>'添加失败','data'=>$model->getErrors()]);
    }

    public function actionDel()
    {
        $post = Yii::$app->request->post();
        if (!empty($post)){
            $model=ClubsApplicants::find()->where(['user_id'=>$post['user_id'],'club_id'=>$post['club_id']])->one();
            if ($model->delete()){
                return json_encode(['code'=>200,'message'=>'退出成功']);
            }
        }
        return json_encode(['code'=>500,'message'=>'退出失败']);
    }

    public function actionFollow($user_id)
    {
        $data=ClubsApplicants::find()->where(['user_id'=>$user_id])->select("user_id,club_id")->asArray()->all();
        if ($data){
            foreach ($data as $key => $value){
                $clubs=Clubs::find()->where(['id'=>$data[$key]['club_id']])->asArray()->one();
                $data[$key]['club_name']=$clubs['club_name'];
                $data[$key]['club_activity']=$clubs['club_activity'];
            }
            return json_encode(['code'=>200,'message'=>'获取数据成功','data'=>$data]);
        }
        return json_encode(['code'=>500,'message'=>'空数据']);
    }

    public function actionClubPeople($id)
    {
        $user_ids=ClubsApplicants::find()->where(['club_id'=>$id])->asArray()->all();
        if ($user_ids){
            //var_dump($applicant_ids);exit();
            foreach ($user_ids as $e => $o){
                $users[]=Users::find()->where(['id'=>$o['user_id']])->select('id,username,head_url')->asArray()->one();
            }
            $last_names = array_column($users,'username');
            array_multisort($last_names);
            //sort($last_names);
            //var_dump($last_names);exit();
            //$data=Users::find()->select('username')->orderBy('username')->asArray()->all();
            foreach ($last_names as $key => $value)
            {
                $first[]=substr($value,0,1);
            }
            //var_dump($first);exit();
            $first=array_unique($first);
            //var_dump($first);exit();
            $connection = Yii::$app->db;

            foreach ($first as $val){
                $sql='SELECT users.id,users.username,users.head_url FROM users JOIN clubs_applicants on users.id=clubs_applicants.user_id WHERE clubs_applicants.club_id='.$id.' and users.username LIKE "'.$val.'%"';
                $command=$connection->createCommand($sql);
                $s=$command->queryAll();
                foreach ($s as $k => $m){
                    $model[$val][$k]['id']=$m['id'];
                    $model[$val][$k]['username']=$m['username'];
                    $model[$val][$k]['head_url']=$m['head_url'];
                }
            }
            //var_dump($model);exit();
            return json_encode(['code'=>200,'message'=>'获取数据成功','data'=>$model]);
        }
        return json_encode(['code'=>500,'message'=>'空数据']);
    }

    public function actionSearch($id)
    {
        $post=Yii::$app->request->post();
        if (!empty($post['username']))
        {
            $connection = Yii::$app->db;
            $sql='SELECT users.id,users.username,users.head_url FROM users JOIN clubs_applicants on users.id=clubs_applicants.user_id WHERE clubs_applicants.club_id='.$id.' and users.username = "'.$post['username'].'"';
            $command=$connection->createCommand($sql);
            $s=$command->queryOne();
            if ($s){
                return json_encode(['code'=>200,'message'=>'获取数据成功','data'=>$s]);
            }
            return json_encode(['code'=>500,'message'=>'空数据']);
        }
        return json_encode(['code'=>500,'message'=>'请求不能为空']);
    }

    public function actionDele()
    {
        $post=Yii::$app->request->post();
        if (!empty($post))
        {
            $model=ClubsApplicants::find()->where(['user_id'=>$post['user_id'],'club_id'=>$post['club_id']])->one();
            if ($model){
                if ($model->delete())
                {
                    return json_encode(['code' => 200, 'message' => '删除成功']);
                }
            }
        }
        return json_encode(['code'=>500,'message'=>'删除失败']);
    }
}
