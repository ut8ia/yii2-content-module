<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel ut8ia\contentmodule\models\ContentSectionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Content Sections');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-sections-index">
    <p>
        <?= Html::a(Yii::t('app', 'Create Content Sections'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['class' => 'col-sm-1 small text-right', 'nowrap' => 'nowrap']
            ],
            [
                'contentOptions' => ['class' => 'col-sm-1 small text-right'],
                'attribute' => 'id',
                'format' => 'html',
                'value' => function($model) {
                    return $model->id;
                },
            ],
            [
                'contentOptions' => ['class' => 'col-sm-11 small text-right'],
                'attribute' => 'name',
                'format' => 'html',
                'value' => function($model) {
                    return Yii::t('app', $model->name);
                },
            ],
        ],
    ]); ?>
</div>
