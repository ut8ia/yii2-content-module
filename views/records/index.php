<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel ut8ia\contentmodule\models\ContentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Content');
?>
<div class="content-index">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a(Yii::t('app', 'Create Content'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php


    $columns[] = [
        'class' => 'yii\grid\ActionColumn',
        'contentOptions' => ['class' => 'small text-right', 'nowrap' => 'nowrap']
    ];

    if (Yii::$app->controller->module->multilanguage) {
        $columns[] = [
            'contentOptions' => [
                'class' => 'small text-left'
            ],
            'format' => 'html',
            'value' => function($model) {
                switch ($model->language->url) {
                    case 'en':
                        $class = "label-info";
                        break;
                    case 'ru':
                        $class = "label-danger";
                        break;
                    default :
                        $class = "label-default";
                }
                $lng = "<span class='label " . $class . "'>" . strtoupper($model->language->url) . "</span>";
                return $lng;
            },
        ];
    }

    $columns[] = [
        'contentOptions' => ['class' => 'col-sm-4 small text-left'],
        'attribute' => 'Name',
        'format' => 'html',
        'value' => function($model) {
            return $model->name;
        },
    ];

    $columns[] = ['contentOptions' => ['class' => 'col-sm-2 small text-left'],
        'attribute' => 'Rubric',
        'format' => 'html',
        'value' => function($model) {

            if (isset($model->rubric)) {
                $rubric = $model->rubric->name_ru;
                return $rubric;
            }
        },
    ];

    $columns[] = [
        'contentOptions' => ['class' => 'col-sm-4 text-left'],
        'attribute' => 'Tags',
        'format' => 'html',
        'value' => function($model) {
            if (isset($model->tags)) {
                $tags = ArrayHelper::toArray($model->tags);
                $out = "";
                foreach ($tags as $ind => $val) {
                    switch ($val['type']) {
                        case 1:
                            $class = "label-warning";
                            break;
                        case 2:
                            $class = "label-success";
                            break;
                        default :
                            $class = "label-default";
                    }

                    $out .= " <span class='label " . $class . "' style ='padding-top:1px;'>" . $val['name'] . "</span>";
                }

                return $out;
            }
        },
    ];

    $columns[] = [
        'contentOptions' => ['class' => 'col-sm-2 small text-center'],
        'attribute' => 'Date',
        'format' => 'html',
        'value' => function($model) {
            return $model->date;
        },
    ];


    echo GridView::widget(['dataProvider' => $dataProvider,
        //    'filterModel' => $searchModel,
        'columns' => $columns
    ]);
    ?>

</div>
