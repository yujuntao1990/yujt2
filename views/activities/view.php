<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Activities */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Activities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activities-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'content',
//            'cate_id',
            [   'attribute'=>'cate_id',
                'value' => $model->cates->content,
            ],
            'reg_time_start',
            'reg_time_end',
            'entry_fee',
//            'sports_id',
            [   'attribute'=>'sports_id',
                'value' => $model->sports->content,
            ],
            'contact',
            'user_id',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
