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
        $form->field($model, 'section_id', [
            'template' => '<div class="col-lg-4">{input}</div>',
            'options' => ['class' => 'inline']])->dropDownList(ContentSections::selector());
        ?>

        <?=
        $form->field($model, 'rubric_id', [
            'template' => '<div class="col-lg-4">{input}</div>',
            'options' => ['class' => 'inline']])->dropDownList(ContentRubrics::selector($model->section_id));
        ?>

        <?=
        $form->field($model, 'content_type', [
            'template' => '<div class="col-lg-2">{input}</div>',
            'options' => ['class' => 'inline']])->dropDownList($model->contentTypes);
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
    echo $form->field($model,'description')->textarea();
    ?>

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
        'options' => ['multiple' => true, 'placeholder' => 'tags for locate item on page template'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <div class="form-group row">
        <div class="col-lg-2 small"><?php echo Yii::t('main', 'Date'); ?></div>
        <?= $form->field($model, 'date', [
            'template' => '<div class="col-lg-5">{input}</div>',
            'options' => ['class' => 'inline']])->widget(DateTimePicker::class);
        ?>
        <?= $form->field($model, 'lang_id', [
            'template' => '<div class="col-lg-5">{input}</div>',
            'options' => ['class' => 'inline']])->dropDownList(Lang::selector());
        ?>
    </div>

    <div class="form-group row">
        <div class="col-lg-2 small text-left"><?= Yii::t('main', 'Publication'); ?></div>
        <?= $form->field($model, 'publication_date', [
            'template' => '<div class="col-lg-5">{input}</div>',
            'options' => ['class' => 'inline']
        ])
            ->widget(DateTimePicker::class);
        ?>
        <?= $form->field($model, 'published', [
            'template' => '<div class="col-lg-5">{input}</div>',
            'options' => ['class' => 'inline']
        ])
            ->checkbox();
        ?>
    </div>

    <div class="form-group row">
        <div class="col-lg-2 small text-left"><?= Yii::t('main', 'Display format'); ?></div>
        <?= $form->field($model, 'display_format', [
            'template' => '<div class="col-lg-5">{input}</div>',
            'options' => ['class' => 'inline']])
            ->textInput();

        ?>
    </div>

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
            <?= $form->field($model, 'stick')->checkbox(); ?>
        </div>

    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
