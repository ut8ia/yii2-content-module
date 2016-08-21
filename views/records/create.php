<?php

use ut8ia\multylang\models\Lang;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model ut8ia\contentmodule\models\Content */

$this->title = Yii::t('app', 'Create Content');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Content'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// lang id to current
$model->lang_id = Lang::getCurrent()->id;

?>
<div class="content-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
