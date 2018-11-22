<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Activities */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="activities-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cate_id')->dropDownList($model->getCate()) ?>

    <?= $form->field($model, 'reg_time_start')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reg_time_end')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'entry_fee')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sports_id')->dropDownList($model->getSport()) ?>

    <?= $form->field($model, 'contact')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
