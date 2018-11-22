<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Applicants */

$this->title = 'Create Applicants';
$this->params['breadcrumbs'][] = ['label' => 'Applicants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="applicants-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
