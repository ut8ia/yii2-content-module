<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ArticleRubrics */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Article Rubrics',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Article Rubrics'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="article-rubrics-update">
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
