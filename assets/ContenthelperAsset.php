<?php

namespace ut8ia\contentmodule\assets;

use yii\web\AssetBundle;

class ContenthelperAsset extends AssetBundle
{
    public $sourcePath = '@vendor/ut8ia/yii2-content-module/assets';

    public $css = [
    ];
    public $js = [
        'js/contenthelper.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset'
    ];
}
