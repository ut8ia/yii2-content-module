<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model ut8ia\contentmodule\models\ContentRubrics */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Content Rubrics',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Content Rubrics'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="content-rubrics-update">
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
