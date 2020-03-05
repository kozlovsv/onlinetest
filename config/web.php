<?php

use yii\authclient\Collection;

$secure = parse_ini_file(__DIR__ .'/secure.ini', true);

$config = [
    'id' => 'basic',
    'name' => 'Словарные слова',
    'language' => 'ru',
    'sourceLanguage' => 'ru-RU',
    'charset' => 'UTF-8',
    'basePath' => dirname(__DIR__),
    'homeUrl' => '/',
    'bootstrap' => [
        'log',
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'auth' => [
            'class' => 'app\modules\auth\Module',
        ],
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'w7RojP9ecfQT3gXJjGiRnzxFmE',
        ],
        'authManager' => [
            'class' => 'app\components\AuthManager',
            'cache' => 'cache',
        ],
        'authClientCollection' => [
            'class'   => Collection::class,
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'clientId' => $secure['oauth_google_client_id'],
                    'clientSecret' => $secure['oauth_google_client_secret'],
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'assetManager' => [
            'class' => 'yii\web\AssetManager',
            'appendTimestamp' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js'
                    ],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [
                        YII_ENV_DEV ? 'css/bootstrap.css' : 'css/bootstrap.min.css',
                    ],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'js/bootstrap.js' : 'js/bootstrap.min.js',
                    ],
                ],
            ],
        ],
        'formatter' => [
            'class' => 'app\components\Formatter',
            'nullDisplay' => '',
            'locale' => 'ru_RU',
            'dateFormat' => 'dd.MM.yyyy',
            'datetimeFormat' => 'dd.MM.yyyy HH:mm',
            'decimalSeparator' => '.',
            'thousandSeparator' => ' ',
            'timeZone' => 'UTC', //Устанавливаем зону в UTC. Чтобы при конвертации не переводило в другую зону. Так как все данные храним в Зоне Екатеринбурга.
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => require(__DIR__ . '/mail.php'),
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning'],
                    'except' => [
                        'yii\web\HttpException:404',
                        'yii\web\HttpException:403',
                    ],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'baseUrl' => YII_ENV_DEV ? 'http://words.loc' : 'http://onlinetest.atonex.ru',
            'rules' => [
                'login' => 'site/login',
                'logout' => 'site/logout',
                'request-password-reset' => 'site/request-password-reset',
                'reset-password' => 'site/reset-password',
                '<controller:[\w-]+>' => '<controller>/index',
                '<controller:[\w-]+>/<id:\d+>' => '<controller>/view',
                '<controller:[\w-]+>/<action:[\w-]+>/<id:\d+>' => '<controller>/<action>',
            ],
        ],
    ],
    'as beforeRequest' => [
        'class' => 'app\components\RequestAccess',
    ],
    'params' => require(__DIR__ . '/params.php'),
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['192.168.*', '127.0.0.1', '::1'],
    ];
    $config['bootstrap'][] = 'gii';

    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['192.168.*', '127.0.0.1', '::1'],
        'generators' => [ //here
            'atonex-crud' => [ // generator name
                'class' => 'app\generators\crud\Generator', // generator class
            ]
        ],
    ];

    /**
     * Debug function
     * d($var);
     * @param $var
     * @param null $caller
     */
    function d($var,$caller=null)
    {
        if(!isset($caller)){
            $caller = array_shift(debug_backtrace(1));
        }
        echo '<code>File: '.$caller['file'].' / Line: '.$caller['line'].'</code>';
        echo '<pre>';
        yii\helpers\VarDumper::dump($var, 10, true);
        echo '</pre>';
    }

    /**
     * Debug function with die() after
     * dd($var);
     * @param $var
     */
    function dd($var)
    {
        $caller = array_shift(debug_backtrace(1));
        d($var,$caller);
        die();
    }
}

return $config;
