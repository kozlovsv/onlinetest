<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * PNotify Asset Bundle.
 */
class PNotifyAsset extends AssetBundle
{
    public $sourcePath = '@bower/pnotify/dist';
    public $js = [
        'pnotify.js',
        'pnotify.buttons.js',
    ];
    public $css = [
        'pnotify.css',
        'pnotify.buttons.css',
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}