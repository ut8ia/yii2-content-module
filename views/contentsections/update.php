<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model ut8ia\contentmodule\models\ContentSections */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Content Sections',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Content Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="content-sections-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
