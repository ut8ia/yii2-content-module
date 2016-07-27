<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Article Rubrics');
?>
<div class="article-rubrics-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Article Rubrics'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=     GridView::widget([
        'dataProvider' => $dataProvider,
    //    'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['class' => 'col-sm-1 small text-right', 'nowrap' => 'nowrap'],],
            [
                'contentOptions' => ['class' => 'col-sm-1 small text-right'],
                'attribute' => 'id',
                'format' => 'html',
                'value' => function ($model) {
            return $model->id;
        },
            ],
            [
                'contentOptions' => ['class' => 'col-sm-7 small text-left'],
                'attribute' => 'Name',
                'format' => 'html',
                'value' => function ($model) {
            return $model->name_ru;
        },
            ],
            [
                'contentOptions' => ['class' => 'col-sm-7 small text-left'],
                'attribute' => 'Name',
                'format' => 'html',
                'value' => function ($model) {
            return $model->name_en;
        },
            ],
        ],
    ]); ?>

</div>
