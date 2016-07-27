<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log',
        'app\components\ThemeBootstrap',
    ],
    'components' => [
        'view' => [
            'theme' => [
                'pathMap' => ['@app/views' => '@app/themes/advance'],
                'baseUrl' => '@app/themes/advance',
            ],
        ],
        'mobileDetect' => [
            'class' => '\ezze\yii2\mobiledetect\MobileDetect'
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '2321321j0AoPQl9YEyhasEWcmx321312',
        ],
        'cache' => [
            //'class' => 'yii\caching\FileCache',
            'class' => 'yii\caching\ApcCache',
            'servers' => [
                [
                    'host' => 'msp-rp.lc',
                    'port' => 11211,
                    'weight' => 100,
                ],
                [
                    'host' => 'msp-rp.lc',
                    'port' => 11211,
                    'weight' => 50,
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManagerApi' => [
            'class' => 'yii\web\UrlManager',
            'scriptUrl' => "http://api.kara.appwhoosh.com/",
            //'scriptUrl' => "http://msp-api.lc/",
            'baseUrl' => "http://api.kara.appwhoosh.com/",
            //'baseUrl' => "http://msp-api.lc/",
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,

        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'scriptUrl' => "",
            'baseUrl' => "",
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,

        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
