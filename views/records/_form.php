<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker;
use ut8ia\filemanager\widgets\TinyMCE;
use kartik\select2\Select2;
use ut8ia\multylang\models\Lang;
use ut8ia\contentmodule\models\ContentRubrics;
use ut8ia\contentmodule\models\ContentSections;
use ut8ia\contentmodule\models\Tags;

$model->SystemTags = $model->getLinkedTagsByType($model->id, 1, 0, null);
$model->NavTags = $model->getLinkedTagsByType($model->id, 2, 0, null);
$tags = new Tags();

?>
<div class="content-form">

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

    <div class="form-group row">
        <div class="col-lg-2 small"><?php echo Yii::t('main', 'Header'); ?></div>
        <?=
        $form->field($model, 'name', [
            'template' => '<div class="col-lg-5">{input}</div>',
            'options' => ['class' => 'inline']])
            ->textInput(['maxlength' => true])
        ?>

        <?=
        $form->field($model, 'rubric_id', [
            'template' => '<div class="col-lg-5">{input}</div>',
            'options' => ['class' => 'inline']])
            ->dropDownList(ContentRubrics::selector($model->section_id));
        ?>

    </div>

    <?php

    echo $form->field($model, 'text')->widget(TinyMce::class, [
        'clientOptions' => [
            'language' => 'ru',
            'menubar' => false,
            'height' => 500,
            'image_dimensions' => false,
            'apply_source_formatting' => false,
            'verify_html' => false,
            'plugins' => [
                'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code contextmenu table paste insertdatetime',
            ],
            'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code preview ',
        ],
    ]);

    ?>

    <iframe id="form_target" name="form_target" style="display:none">
        <form id="my_form" action="/upload/" target="form_target" method="post" enctype="multipart/form-data"
              style="width:0px;height:0;overflow:hidden">
            <input name="image" type="file" onchange="$('#my_form').submit();this.value='';">
        </form>
    </iframe>


    <?php
    echo $form->field($model, 'NavTags')->widget(Select2::class, [
        'data' => $tags->getByType(2, 1),
        'language' => 'en',
        'options' => [
            'multiple' => true,
            'placeholder' => 'Tags for navigation and seo'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?php
    echo $form->field($model, 'SystemTags')->widget(Select2::class, [
        'data' => $tags->getByType(1, 1),
        'language' => 'en',
        'options' => ['multiple' => true, 'placeholder' => 'System tags like *main* *announce* e.t.c. '],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <div class="form-group row">
        <div class="col-lg-2 small"><?php echo Yii::t('main', 'Date'); ?></div>


        <?=
        $form->field($model, 'date', [
            'template' => '<div class="col-lg-5">{input}</div>',
            'options' => ['class' => 'inline']])->widget(DateTimePicker::class);
        ?>


        <?php

        if (Yii::$app->controller->module->multilanguage) {
            echo $form->field($model, 'lang_id', [
                'template' => '<div class="col-lg-5">{input}</div>',
                'options' => ['class' => 'inline']])->dropDownList(Lang::selector());
        }
        ?>
    </div>

    <div class="row">
        <div class="col-lg-2 small text-left"><?= Yii::t('main', 'Author'); ?></div>
        <div class="col-lg-5">
            <?php
            if (isset($model->author)) {
                echo $model->author->username;
            }
            ?>
        </div>
        <div class="col-lg-5">
            <?= $form->field($model, 'stick')->checkbox(); ?>
        </div>

    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
