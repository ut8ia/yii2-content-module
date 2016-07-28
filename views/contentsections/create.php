<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model ut8ia\contentmodule\models\ContentSections */

$this->title = Yii::t('app', 'Create Content Sections');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Content Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-sections-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
