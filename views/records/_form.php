<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker;
use ut8ia\filemanager\widgets\TinyMce;
use kartik\select2\Select2;
use ut8ia\multylang\models\Lang;
use ut8ia\contentmodule\models\ContentRubrics;
use ut8ia\contentmodule\models\Tags;
use conquer\codemirror\CodemirrorWidget;
use conquer\codemirror\CodemirrorAsset;

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

    if ($model->content_type == 'javascript') {
        echo $form->field($model, 'text')->widget(
            CodemirrorWidget::class,
            [
                'preset' => 'javascript',
                'assets' => [
                    CodemirrorAsset::MODE_CLIKE,
                    CodemirrorAsset::KEYMAP_EMACS,
                    CodemirrorAsset::ADDON_EDIT_MATCHBRACKETS,
                    CodemirrorAsset::ADDON_COMMENT,
                    CodemirrorAsset::ADDON_DIALOG,
                    CodemirrorAsset::ADDON_SEARCHCURSOR,
                    CodemirrorAsset::ADDON_SEARCH,
                ],
                'options' => ['rows' => 20],
                'settings' => [
                    'mode' => 'javascript'
                ]
            ]
        );
    } else {

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
    }
    ?>

    <iframe id="form_target" name="form_target" style="display:none">
        <form id="my_form" action="/upload/" target="form_target" method="post" enctype="multipart/form-data"
              style="width:0px;height:0;overflow:hidden">
            <input name="image" type="file" onchange="$('#my_form').submit();this.value='';">
        </form>
    </iframe>

    <?php
    if (Yii::$app->controller->module->source) {
        echo $form->field($model, 'source')->textInput();
    }
    ?>

    <?php
    if (Yii::$app->controller->module->priority) {
        echo $form->field($model, 'priority')->dropDownList($model->prioritySelector(), ['prompt' => '']);
    }
    ?>


    <?php
    if (Yii::$app->controller->module->contentType) {
        echo $form->field($model, 'content_type')->dropDownList($model->contentTypes);
    }
    ?>

    <?php
    if (Yii::$app->controller->module->description) {
        echo $form->field($model, 'description')->textarea();
    }
    ?>

    <?php
    if (Yii::$app->controller->module->navigationTags) {

        echo $form->field($model, 'NavTags')->widget(Select2::class, [
            'data' => $tags->getByType(2, 1),
            'language' => 'en',
            'options' => [
                'multiple' => true,
                'placeholder' => 'Tags for navigation'
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    }

    ?>

    <?php

    if (Yii::$app->controller->module->positioning) {

        echo $form->field($model, 'SystemTags')->widget(Select2::class, [
            'data' => $tags->getByType(1, 1),
            'language' => 'en',
            'options' => ['multiple' => true, 'placeholder' => 'System tags like *main* *announce* e.t.c. '],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    }
    ?>

    <?php if (Yii::$app->controller->module->multilanguage) { ?>
        <div class="form-group row">
            <div class="col-lg-2 small"><?php echo Yii::t('main', 'Language'); ?></div>
            <?= $form->field($model, 'lang_id', [
                'template' => '<div class="col-lg-5">{input}</div>',
                'options' => ['class' => 'inline']])->dropDownList(Lang::selector());
            ?>
        </div>

        <?php
    }
    ?>
    <?php
    if (Yii::$app->controller->module->publicationShedule || Yii::$app->controller->module->publication) {

        ?>
        <div class="form-group row">
            <div class="col-lg-2 small text-left"><?= Yii::t('main', 'Publication'); ?></div>
            <?php
            if (Yii::$app->controller->module->publicationShedule) {
                echo $form->field($model, 'publication_date', [
                    'template' => '<div class="col-lg-5">{input}</div>',
                    'options' => ['class' => 'inline']
                ])
                    ->widget(DateTimePicker::class);
            }
            ?>

            <?php
            if (Yii::$app->controller->module->publication) {
                echo $form->field($model, 'published', [
                    'template' => '<div class="col-lg-5">{input}</div>',
                    'options' => ['class' => 'inline']
                ])
                    ->checkbox();
            }
            ?>
        </div>
        <?php
    };
    ?>

    <?php
    if (Yii::$app->controller->module->displayFormat) {

        ?>
        <div class="form-group row">
            <div class="col-lg-2 small text-left"><?= Yii::t('main', 'Display format'); ?></div>
            <?php
            $formats = Yii::$app->controller->module->displayFormats;
            if (is_array($formats) and !empty($formats)) {
                echo $form->field($model, 'display_format', [
                    'template' => '<div class="col-lg-5">{input}</div>',
                    'options' => ['class' => 'inline']])
                    ->dropDownList(Yii::$app->controller->module->displayFormats);
            }
            ?>
        </div>
        <?php
    };
    ?>

    <div class="form-group row">
        <div class="col-lg-2 small text-left"><?= Yii::t('main', 'Author'); ?></div>
        <div class="col-lg-5">
            <?php
            if (isset($model->author)) {
                echo $model->author->username;
            }
            ?>
        </div>
        <div class="col-lg-5">
            <?php
            if (Yii::$app->controller->module->stick) {
                $form->field($model, 'stick')->checkbox();
            }
            ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
