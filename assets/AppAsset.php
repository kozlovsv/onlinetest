<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * AppAsset
 * @package app\assets
 */
class AppAsset extends AssetBundle
{
    public $publishOptions = ['forceCopy' => YII_DEBUG];
    public $sourcePath = '@app/assets/src/app';
    public $css = [
        'css/site.css',
    ];
    public $js = [
        'js/main.js',
        '//cdnjs.cloudflare.com/ajax/libs/buzz/1.2.0/buzz.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'kartik\icons\FontAwesomeAsset',
    ];
}
