<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Tags;

/* @var $this yii\web\View */
/* @var $model common\models\Tags */
/* @var $form yii\widgets\ActiveForm */

// default navigation tag type
$model->type = (isset($model->type))?$model->type:2;


?>

<div class="tags-form">

    <?php
    $form = ActiveForm::begin([
                'enableClientValidation' => false,
                'options' => ['class' => 'form-horizontal', 'style' => 'padding-left:0px;'],
                'fieldConfig' => [
                    'template' => '<div class="col-lg-2 small">{label}</div><div class="col-lg-10">{input}{error}</div>',
                    'labelOptions' => ['style' => 'font-weight: lighter;'],
                    'inputOptions' => ['class' => 'form-control'],
                ],
    ]);
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(Tags::getSelector()); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
