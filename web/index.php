<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

if (!function_exists('getallheaders')) {
    function getallheaders() {
        foreach($_SERVER as $key=>$value) {
            if (substr($key,0,5)=="HTTP_") {
                $key=str_replace(" ","-",(str_replace("_"," ",substr($key,5))));
                $out[$key]=$value;
            }else{
                $out[$key]=$value;
            }
        }
        return $out;
    }
}

date_default_timezone_set('Asia/Ho_Chi_Minh');
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
//$config = require(__DIR__ . '/../helpers/SSO/config.php');
Yii::$classMap['phpCAS'] = __DIR__ . '/../helpers/SSO/CAS.php';

(new yii\web\Application($config))->run();
