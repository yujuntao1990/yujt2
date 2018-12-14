<?php

namespace app\controllers;

use app\models\Activities;
use Yii;
use app\models\Applicants;
use app\models\ApplicantsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * ApplicantsController implements the CRUD actions for Applicants model.
 */
class ApplicantsController extends BaseController
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
     * Lists all Applicants models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ApplicantsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Applicants model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $data=$this->findModel($id);
        $data=ArrayHelper::toArray($data);
        if (!empty($data)){
            return json_encode(['code'=>200,'message'=>'获取数据成功','data'=>$data]);
        };
        return json_encode(['code'=>500,'message'=>'空数据']);
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
    }

    /**
     * Creates a new Applicants model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Applicants();

        $post = Yii::$app->request->post();
        if (!empty($post)) {
            $model->username=$post['username'];
            $model->tel=$post['tel'];
            $model->card=$post['card'];
            $model->user_id=$post['user_id'];
            if ($model->save()){
                return json_encode(['code'=>200,'message'=>'添加成功']);
            }
//            return $this->redirect(['view', 'id' => $model->id]);
        }
        return json_encode(['code'=>500,'message'=>'添加失败','data'=>$model->getErrors()]);
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
    }

    /**
     * Updates an existing Applicants model.
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
     * Deletes an existing Applicants model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $post = Yii::$app->request->post();
        if (!empty($post)) {
            $model=Applicants::findOne($post['id']);
            if ($model) {
                if ($model->delete()) {
                    return json_encode(['code' => 200, 'message' => '删除成功']);
                }
            }
        }
        return json_encode(['code'=>500,'message'=>'删除失败']);
    }

    /**
     * Finds the Applicants model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Applicants the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Applicants::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionList($user_id)
    {
        $data=Applicants::find()->where(['user_id'=>$user_id])->asArray()->all();
        if (!empty($data)){
                return json_encode(['code'=>200,'message'=>'获取数据成功','data'=>$data]);
        };
        return json_encode(['code'=>500,'message'=>'空数据']);
    }

}
