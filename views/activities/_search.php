<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ActivitiesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="activities-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'content') ?>

    <?= $form->field($model, 'cate_id')->dropDownList($model->getCate()) ?>

    <?= $form->field($model, 'reg_time_start') ?>

    <?php // echo $form->field($model, 'reg_time_end') ?>

    <?php // echo $form->field($model, 'entry_fee') ?>

    <?php // echo $form->field($model, 'sports_id') ?>

    <?php // echo $form->field($model, 'contact') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
