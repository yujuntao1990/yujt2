<?php

namespace app\controllers;

use app\models\ActivitiesPhotos;
use app\models\ActivityCollect;
use app\models\Applicants;
use app\models\Comment;
use app\models\Groups;
use app\models\Lifes;
use app\models\OrderApplicant;
use app\models\Orders;
use app\models\Qinzis;
use app\models\Users;
use app\models\Weekends;
use Yii;
use app\models\Activities;
use app\models\ActivitiesSearch;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ActivitiesController implements the CRUD actions for Activities model.
 */
class ActivitiesController extends BaseController
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
     * Lists all Activities models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ActivitiesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Activities model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $data=$this->findModel($id);
        $data=ArrayHelper::toArray($data);
        //var_dump($data);exit();
        if (!empty($data)){
            $data['username']=Users::findOne($data['user_id'])->username;
            $data['user_head']=Users::findOne($data['user_id'])->head_url;
            $data['comments']=Comment::find()->where(['activity_id'=>$id])->asArray()->all();
            if (!empty($data['comments'])){
                foreach ($data['comments'] as $key => $value){
                    $data['comments'][$key]['head_url']=Users::findOne($data['comments'][$key]['user_id'])->head_url;
                    $data['comments'][$key]['username']=Users::findOne($data['comments'][$key]['user_id'])->username;
                }
            }
            $data['applicants']=Applicants::find()->where(['activity_id'=>$id])->asArray()->all();
            $activity_photos=ActivitiesPhotos::find()->where(['activity_id'=>$id])->asArray()->all();
            if (!empty($activity_photos)){
                foreach ($activity_photos as $key => $value){
                    $data['activity_photos'][$key]=$value['activity_photo'];
                }
            }

            return json_encode(['code'=>200,'message'=>'获取数据成功','data'=>$data]);
        }
        return json_encode(['code'=>500,'message'=>'空数据']);
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
    }

    /**
     * Creates a new Activities model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Activities();
        $post = Yii::$app->request->post();

        $transaction = Yii::$app->db->beginTransaction();
        try{
            if (!empty($post)) {
                $model->title=$post['title'];
                $model->content=$post['content'];
                $model->cate_id=$post['cate_id'];
                $model->reg_time_start=$post['reg_time_start'];
                $model->reg_time_end=$post['reg_time_end'];
                $model->people_num=$post['people_num'];
                $model->address=$post['address'];
                $model->entry_fee=$post['entry_fee'];
                $model->sports_id=$post['sports_id'];
                $model->contact=$post['contact'];
                $model->user_id=$post['user_id'];
                //var_dump($file);exit();

                if (!$model->validate()){
                    throw new Exception($model->getErrors());
                }
                if (isset($_FILES['photo_url'])){
                    $model->photo_url=time().$_FILES['photo_url']["name"];
                }
                if (!$model->save(false)) {
                    throw new Exception('添加失败');
                }
                if (empty($post['groups'])){
                    throw new Exception('组不能为空');
                }
                foreach ($post['groups'] as $key => $value){
                    $group=new Groups();
                    $group->activity_id=$model->id;
                    $group->name=$post['groups'][$key];
                    if (!$group->save()){
                        throw new Exception($model->getErrors());
                    }
                }
                if (isset($_FILES['photo_url'])){
                    move_uploaded_file($_FILES['photo_url']["tmp_name"], "activities/" . time().$_FILES['photo_url']["name"]);
                    //echo "文件存储在: " . "upload/" . $file["name"];
                }
                //var_dump($post['groups']);exit();
                $transaction->commit();
                return json_encode(['code' => 200, 'message' => '添加成功']);
//            return $this->redirect(['view', 'id' => $model->id]);
            }
        }catch (Exception $e){
            $transaction->rollBack();
            return json_encode(['code'=>500,'message'=>'添加失败','data'=>$e->getMessage()]);
        }
//            return $this->render('create', [
//                'model' => $model,
//            ]);
    }

    /**
     * Updates an existing Activities model.
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
     * Deletes an existing Activities model.
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
     * Finds the Activities model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Activities the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Activities::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionList()
    {
        $data = Activities::find()->asArray()->all();
        if (!empty($data)){
            return json_encode(['code'=>200,'message'=>'获取数据成功','data'=>$data]);
        }
        return json_encode(['code'=>500,'message'=>'空数据']);
    }

    public function actionComments($id)
    {
        $comments=Comment::find()->where(['activity_id'=>$id])->asArray()->all();
        //var_dump($comments);exit();
        if (!empty($comments)){
            foreach ($comments as $key => $value){
                $user=Users::findOne($comments[$key]['user_id']);
                $comments[$key]['username']=$user->username;
                $comments[$key]['head_url']=$user->head_url;
            }
            return json_encode(['code'=>200,'message'=>'获取数据成功','data'=>$comments]);
        };
        return json_encode(['code'=>500,'message'=>'空数据']);
    }

    public function actionPrice($orderby)
    {
        $data=Activities::find()->orderBy('entry_fee '.$orderby)->asArray()->all();
        if (!empty($data)){
            return json_encode(['code'=>200,'message'=>'获取数据成功','data'=>$data]);
        };
        return json_encode(['code'=>500,'message'=>'空数据']);
    }


    public function actionDateSearch()
    {
        if (Yii::$app->request->isPost){
            $post=Yii::$app->request->post();
            $datatime=strtotime($post['datetime']);
            $data=Activities::find()->where(['>=','reg_time_end',$datatime])->andWhere(['<','reg_time_end',$datatime+24*3600])->asArray()->all();
            //var_dump($data->createCommand()->getRawSql());exit();
            if (!empty($data)){
                return json_encode(['code'=>200,'message'=>'获取数据成功','data'=>$data]);
            }
            return json_encode(['code'=>500,'message'=>'空数据']);
        }
        return json_encode(['code'=>500,'message'=>'时间不能为空']);
    }


    public function actionAdd()
    {
        $model=new ActivityCollect();
        $post = Yii::$app->request->post();
        if (!empty($post)){
            $model->user_id=$post['user_id'];
            $model->activity_id=$post['activity_id'];
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
            $model=ActivityCollect::find()->where(['user_id'=>$post['user_id'],'activity_id'=>$post['activity_id']])->one();
            if ($model->delete()){
                return json_encode(['code'=>200,'message'=>'删除成功']);
            }
        }
        return json_encode(['code'=>500,'message'=>'删除失败']);
    }

    public function actionFollow($user_id)
    {
        $data=ActivityCollect::find()->where(['user_id'=>$user_id])->select("user_id,activity_id")->asArray()->all();
        if ($data){
            foreach ($data as $key => $value){
                $Activities=Activities::find()->where(['id'=>$data[$key]['activity_id']])->asArray()->one();
                $data[$key]['title']=$Activities['title'];
                $data[$key]['sports_id']=$Activities['sports_id'];
            }
            return json_encode(['code'=>200,'message'=>'获取数据成功','data'=>$data]);
        }
        return json_encode(['code'=>500,'message'=>'空数据']);
    }

    public function actionBaoming($id)
    {
        $model=$this->findModel($id);
        $model=ArrayHelper::toArray($model);
        if ($model){
            $groups=Groups::find()->where(['activity_id'=>$id])->asArray()->all();
            if ($groups){
                $model['groups']=$groups;
            }
            $applicants=Applicants::find()->where(['activity_id'=>$id])->select('username,tel,card')->asArray()->all();
            if ($applicants){
                $model['applicants']=$applicants;
            }
            return json_encode(['code'=>200,'message'=>"获取数据成功",'data'=>$model]);
        }
        return json_encode(['code'=>500,'message'=>'空数据']);
    }

    public function actionTt()
    {
//        spl_autoload_unregister(['YiiBase', 'autoload']);
//        include('../lib/Yu.php');
//        $yu=new \Yu();
//        $yu->ni();
        $model=Users::className();
        var_dump($model);
    }

    public function actionSumup($id)
    {
        $post=Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try{
            if (!empty($post)){
                $activity=$this->findModel($id);
                $activity->sum_up=$post['sum_up'];
                if (!$activity->save()){
                    throw new Exception($activity->getErrors());
                }
                if (isset($_FILES['activity_photo'])){
                    foreach ($_FILES['activity_photo']['name'] as $key => $value){
                        $activity_photo=new ActivitiesPhotos();
                        move_uploaded_file($_FILES['activity_photo']["tmp_name"][$key], "activities/" . time() . $_FILES['activity_photo']["name"][$key]);
                        $activity_photo->activity_photo=time().$_FILES['activity_photo']["name"][$key];
                        $activity_photo->activity_id=$id;
                        $activity_photo->user_id=$post['user_id'];
                        if (!$activity_photo->save())
                        {
                            throw new Exception(current($activity->getErrors()));
                        }
                    }
                }
                $transaction->commit();
                return json_encode(['code' => 200, 'message' => '添加成功']);
            }
        }catch (Exception $e){
            $transaction->rollBack();
            return json_encode(['code'=>500,'message'=>'添加失败','data'=>$e->getMessage()]);
        }
    }

    public function actionOrder()
    {
        $post=Yii::$app->request->post();
        $transaction=Yii::$app->db->beginTransaction();
        try{
            if (!empty($post)){
                $orders=new Orders();
                $orders->order_number=uniqid();
                $orders->activity_id=$post['activity_id'];
                $orders->no_status=$post['no_status'];
                $orders->group_id=$post['group_id'];
                $orders->already_arrived=$post['already_arrived'];
                $orders->money=$post['money'];
                $orders->user_id=$post['user_id'];
                if (!$orders->save()){
                    throw new Exception($orders->getErrors());
                }
                if (empty($post['applicant_id'])){
                    throw new Exception("报名者不能为空");
                }
                foreach ($post['applicant_id'] as $key => $value){
                    $orderapplicant=new OrderApplicant();
                    $orderapplicant->order_id=$orders->id;
                    $orderapplicant->applicant_id=$value;
                    if (!$orderapplicant->save()){
                        throw new Exception($orders->getErrors());
                    }
                }
                $transaction->commit();
                return json_encode(['code' => 200, 'message' => '添加成功']);
            }
        }catch (Exception $e){
            $transaction->rollBack();
            return json_encode(['code'=>500,'message'=>'添加失败','data'=>$e->getMessage()]);
        }
    }

    public function actionOrders($user_id)
    {
        $orders=Orders::findAll(['user_id'=>$user_id]);
        $orders=ArrayHelper::toArray($orders);
        if (!empty($orders)){
            foreach ($orders as $key => $value){
                $activity=Activities::findOne($orders[$key]['activity_id']);
                $orders[$key]['title']=$activity->title;
                $orders[$key]['content']=$activity->content;
                $orders[$key]['baomingtime']=$activity->reg_time_end;
            }
            return json_encode(['code'=>200,'message'=>"获取数据成功",'data'=>$orders]);
        }
        return json_encode(['code'=>500,'message'=>'空数据']);
    }

    public function actionDetail($order_id)
    {
        $order=Orders::findOne($order_id);
        $order=ArrayHelper::toArray($order);
        if (!empty($order)){
            $activity=Activities::find()->where(['id'=>$order['activity_id']])->select(['title','reg_time_start','reg_time_end','people_num','address'])->asArray()->one();
            $order['activity']=$activity;
            return json_encode(['code'=>200,'message'=>"获取数据成功",'data'=>$order]);
        }
        return json_encode(['code'=>500,'message'=>'空数据']);
    }

    public function actionValidate($order_id)
    {
        $post=Yii::$app->request->post();
        if (!empty($post)){
            $order=Orders::findOne($order_id);
            if ($order){
                $order->already_arrived=$post['already_arrived'];
                if ($order->save()){
                    return json_encode(['code' => 200, 'message' => '已验证']);
                }
            }
        }
        return json_encode(['code'=>500,'message'=>'验证失败','data'=>$order->getErrors()]);
    }

    public function actionDele($order_id)
    {
        $model=Orders::findOne($order_id);
        if ($model->delete()){
            return json_encode(['code'=>200,'message'=>'删除成功']);
        }
        return json_encode(['code'=>500,'message'=>'删除失败']);
    }

    public function actionShousuo($cate_id)
    {
        $data=Activities::find()->where(['cate_id'=>$cate_id])->asArray()->all();
        if (!empty($data)) {
            return json_encode(['code' => 200, 'message' => "获取数据成功", 'data' => $data]);
        }
        return json_encode(['code'=>500,'message'=>'空数据']);
    }

    public function actionListSearch()
    {
        $post=Yii::$app->request->post();
        if (!empty($post)){
            $query=Activities::find();
            //var_dump($query);exit();
            if (!empty($post['cate_id'])){
                $query=$query->andWhere(['cate_id'=>$post['cate_id']]);
            }
            if (!empty($post['entry_fee'])){
                //var_dump($post['entry_fee']);exit();
                switch ($post['entry_fee']){
                    case 1:
                        $query=$query->andWhere(['entry_fee'=>0])->andWhere(['entry_fee'=>0]);
                        break;
                    case 2:
                        $query=$query->andWhere(['<','entry_fee',100])->andWhere(['>=','entry_fee',1]);
                        break;
                    case 3:
                        $query=$query->andWhere(['>=','entry_fee',100])->andWhere(['<','entry_fee',300]);
                        break;
                    case 4:
                        $query=$query->andWhere(['>=','entry_fee',300])->andWhere(['<','entry_fee',500]);
                        break;
                    default:
                        $query=$query->andWhere(['>=','entry_fee',500])->andWhere(['>=','entry_fee',500]);
                }
            }
            //var_dump($query->createCommand()->getRawSql());
            //var_dump($query->where);exit();
            if (!empty($post['datetime'])){
                //var_dump($post['datetime']);exit();
                $num=count($query->where);
                if ($num==1){
                    foreach ($post['datetime'] as $key => $value){
                        $s=strtotime($value);
                        $da=$query->andFilterWhere(['>=','reg_time_end',$s])->andFilterWhere(['<','reg_time_end',$s+24*3600])->asArray()->all();
                        if ($da){
                            $data[]=$da;
                        }
                        //var_dump($query->where);exit();
                        unset($query->where[2]);
                        unset($query->where[3]);

                        //var_dump($query);
                    }
                    if (!empty($data)){
                        return json_encode(['code' => 200, 'message' => "获取数据成功", 'data' => $data]);
                    }
                    return json_encode(['code'=>500,'message'=>'空数据']);
                }
                if ($num==3){
                    foreach ($post['datetime'] as $key => $value){
                        $s=strtotime($value);
                        $da=$query->andFilterWhere(['>=','reg_time_end',$s])->andFilterWhere(['<','reg_time_end',$s+24*3600])->asArray()->all();
                        if ($da){
                            $data[]=$da;
                        }
                        unset($query->where[3]);
                        unset($query->where[4]);
                        //var_dump($query->where);exit();
                        //var_dump($query);
                    }
                    if (!empty($data)){
                        return json_encode(['code' => 200, 'message' => "获取数据成功", 'data' => $data]);
                    }
                    return json_encode(['code'=>500,'message'=>'空数据']);
                }
                if ($num==4){
                    foreach ($post['datetime'] as $key => $value){
                        $s=strtotime($value);
                        $da=$query->andFilterWhere(['>=','reg_time_end',$s])->andFilterWhere(['<','reg_time_end',$s+24*3600])->asArray()->all();
                        if ($da){
                            $data[]=$da;
                        }
                        unset($query->where[4]);
                        unset($query->where[5]);
                        //var_dump($query);
                    }
                    if (!empty($data)){
                        return json_encode(['code' => 200, 'message' => "获取数据成功", 'data' => $data]);
                    }
                    return json_encode(['code'=>500,'message'=>'空数据']);
                }
                foreach ($post['datetime'] as $key => $value){
                    $s=strtotime($value);
                    $data[]=$query->andFilterWhere(['>=','reg_time_end',$s])->andFilterWhere(['<','reg_time_end',$s+24*3600])->asArray()->all();
                    $query->where=null;
                    //var_dump($query);
                }
                return json_encode(['code' => 200, 'message' => "获取数据成功", 'data' => $data]);
            }
            //var_dump($query->createCommand()->getRawSql());exit();
            $data=$query->asArray()->all();
            if ($data){
                return json_encode(['code' => 200, 'message' => "获取数据成功", 'data' => $data]);
            }
            return json_encode(['code'=>500,'message'=>'空数据']);
        }
        return json_encode(['code'=>500,'message'=>'请求参数为空']);
    }

    public function actionIndexSearch()
    {
        $post=Yii::$app->request->post();
        if (!empty($post)){
            $query=Activities::find();
            //var_dump($query);exit();
            if (!empty($post['cate_id'])){
                $query=$query->andWhere(['cate_id'=>$post['cate_id']]);
            }
            if (!empty($post['life_id'])){
                $query=$query->andWhere(['life_id'=>$post['life_id']]);
            }
            if (!empty($post['qinzi_id'])){
                $query=$query->andWhere(['qinzi_id'=>$post['qinzi_id']]);
            }
            if (!empty($post['weekend'])){
                $week=strtotime("this ".$post['weekend']);
                $query=$query->andFilterWhere(['>=','reg_time_end',$week])->andFilterWhere(['<','reg_time_end',$week+24*3600]);
            }
            $data=$query->asArray()->all();
            if ($data){
                return json_encode(['code' => 200, 'message' => "获取数据成功", 'data' => $data]);
            }
            return json_encode(['code'=>500,'message'=>'空数据']);
        }
        return json_encode(['code'=>500,'message'=>'请求参数为空']);
    }

    public function actionLifes()
    {
        $data = Lifes::find()->asArray()->all();
        if (!empty($data)){
            return json_encode(['code'=>200,'message'=>'获取数据成功','data'=>$data]);
        }
        return json_encode(['code'=>500,'message'=>'空数据']);
    }

    public function actionQinzis()
    {
        $data = Qinzis::find()->asArray()->all();
        if (!empty($data)){
            return json_encode(['code'=>200,'message'=>'获取数据成功','data'=>$data]);
        }
        return json_encode(['code'=>500,'message'=>'空数据']);
    }

    public function actionWeekends()
    {
        $data = Weekends::find()->asArray()->all();
        if (!empty($data)){
            return json_encode(['code'=>200,'message'=>'获取数据成功','data'=>$data]);
        }
        return json_encode(['code'=>500,'message'=>'空数据']);
    }
}
