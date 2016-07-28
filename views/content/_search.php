<?php

use ut8ia\contentmodule\models\ContentRubrics;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ut8ia\multylang\models\Lang;

/* @var $this yii\web\View */
/* @var $model ut8ia\contentmodule\models\ContentsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="content-search search-block">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?=
    $form->field($model, 'lang_id', [
        'template' => '<div class="col-lg-5">{input}</div>',
        'options' => ['class' => 'inline']])->dropDownList(Lang::selector([''=>'все языки']));
    ?>
    <?=
    $form->field($model, 'rubric_id', [
        'template' => '<div class="col-lg-5">{input}</div>',
        'options' => ['class' => 'inline']])->dropDownList(ContentRubrics::selector([''=>'все рубрики']));
    ?>
    <div class="col-lg-2">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="drop-block"></div>
    <?php ActiveForm::end(); ?>

</div>
