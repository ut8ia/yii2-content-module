<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model ut8ia\contentmodule\models\ContentRubrics */

$this->title = Yii::t('app', 'Create Content Rubrics');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Content Rubrics'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-rubrics-create">
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
