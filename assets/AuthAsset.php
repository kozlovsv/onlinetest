<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Description of AuthAsset
 */
class AuthAsset extends AssetBundle
{
    public $publishOptions = ['forceCopy' => YII_DEBUG];
    public $sourcePath = '@app/assets/src/auth';
    public $js = [
        'auth.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
