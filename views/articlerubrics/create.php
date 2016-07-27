<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ArticleRubrics */

$this->title = Yii::t('app', 'Create Article Rubrics');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Article Rubrics'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-rubrics-create">
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
