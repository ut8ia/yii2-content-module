<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use dosamigos\tinymce\TinyMce;
use dosamigos\datetimepicker\DateTimePicker;
use pendalf89\filemanager\widgets\TinyMCE;
use kartik\select2\Select2;
use common\models\Lang;
use common\models\ArticleRubrics;
use common\models\Tags;


$model->SystemTags = $model->getLinkedTagsByType($model->id, 1, 0, null);
$model->NavTags = $model->getLinkedTagsByType($model->id, 2, 0, null);
$tags = new Tags();

?>
<div class="articles-form">

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
            'options' => ['class' => 'inline']])->textInput(['maxlength' => true])
        ?>

        <?=
        $form->field($model, 'slug', [
            'template' => '<div class="col-lg-5">{input}</div>',
            'options' => ['class' => 'inline']])->textInput(['maxlength' => true])
        ?>
    </div>

    <div class="form-group row">
        <div class="col-lg-2 small"><?php echo Yii::t('main', 'Rubric'); ?></div>
        <?=
        $form->field($model, 'rubric_id', [
            'template' => '<div class="col-lg-10">{input}</div>',
            'options' => ['class' => 'inline']])->dropDownList(ArticleRubrics::selector());
        ?>

    </div>

    <?php

    echo $form->field($model, 'text')->widget(TinyMce::class, [
        'clientOptions' => [
            'language' => 'ru',
            'menubar' => false,
            'height' => 500,
            'image_dimensions' => false,
            'plugins' => [
                'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code contextmenu table paste insertdatetime',
            ],
            'toolbar' => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | code preview ',
        ],
    ]);


    //    echo $form->field($model, 'text')->widget(TinyMce::class, [
//        'options' => ['rows' => 13],
//        'language' => 'ru',
//        'clientOptions' => [
//            'plugins' => [
//                "advlist autolink lists link charmap print preview anchor",
//                "searchreplace visualblocks code fullscreen",
//                "insertdatetime media table contextmenu paste wordcount image"
//            ],
//            'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | preview ",
//            'file_browser_callback'=> new yii\web\JsExpression("function(field_name, url, type, win) {
//            if(type=='image') $('#my_form input').click();
//        }"),
//              'images_upload_url'=> 'postAcceptor.php',
//        ]
//    ]);

    ?>    

    <iframe id="form_target" name="form_target" style="display:none">
    <form id="my_form" action="/upload/" target="form_target" method="post" enctype="multipart/form-data"          style="width:0px;height:0;overflow:hidden">
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


        <?=
        $form->field($model, 'lang_id', [
            'template' => '<div class="col-lg-5">{input}</div>',
            'options' => ['class' => 'inline']])->dropDownList(Lang::selector());
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
