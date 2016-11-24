<?php

use yii\helpers\Html;
use yii\grid\GridView;
use ut8ia\contentmodule\models\Tags;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TagsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tags');
//    dd($tag_types);
?>
<div class="tags-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Tags'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\ActionColumn', 'contentOptions' => ['class' => 'col-sm-1 small text-right', 'nowrap' => 'nowrap'],],
            [
                'contentOptions' => ['class' => 'col-sm-1 small text-left'],
                'attribute' => 'Id',
                'format' => 'html',
                'value' => function($model) {
                    return $model->id;
                }],
            [
                'contentOptions' => ['class' => 'col-sm-7 small text-left'],
                'attribute' => 'Name',
                'format' => 'html',
                'value' => function($model) {
                    return $model->name;
                }],
            [
                'contentOptions' => ['class' => 'col-sm-4 small text-left'],
                'attribute' => 'Type',
                'format' => 'html',
                'value' => function($model) {
                    return Tags::getTypes()[$model->type];
                }],
        ],
    ]);
    ?>

</div>
