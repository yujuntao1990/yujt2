<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Sponsors */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sponsors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sponsors-view">

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
            'sponsors_name',
            'sponsors_about',
            'contact',
            'photo',
            'sponsors_activity',
            'sports_id',
            'money',
            'begin_time',
            'end_time',
            'people_num',
            'address',
            'user_id',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
