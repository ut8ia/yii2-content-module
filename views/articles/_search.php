<?php

use common\models\ArticleRubrics;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Lang;

/* @var $this yii\web\View */
/* @var $model common\models\ArticlesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="articles-search search-block">

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
        'options' => ['class' => 'inline']])->dropDownList(ArticleRubrics::selector([''=>'все рубрики']));
    ?>
    <div class="col-lg-2">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="drop-block"></div>
    <?php ActiveForm::end(); ?>

</div>
