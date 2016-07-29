<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel ut8ia\contentmodule\models\ContentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Content Rubrics');
?>
<div class="content-rubrics-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Content Rubrics'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=     GridView::widget([
        'dataProvider' => $dataProvider,
    //    'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['class' => ' small text-right', 'nowrap' => 'nowrap'],],
            [
                'contentOptions' => ['class' => 'col-sm-6 small text-left'],
                'attribute' => 'Name',
                'format' => 'html',
                'value' => function ($model) {
            return $model->name_ru;
        },
            ],
            [
                'contentOptions' => ['class' => 'col-sm-6 small text-left'],
                'attribute' => 'Name',
                'format' => 'html',
                'value' => function ($model) {
            return $model->name_en;
        },
            ],
        ],
    ]); ?>

</div>
