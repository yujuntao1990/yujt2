<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sponsors */

$this->title = 'Create Sponsors';
$this->params['breadcrumbs'][] = ['label' => 'Sponsors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sponsors-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
