<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/4/15
 * Time: 12:18 PM
 */

namespace app\models;


use yii\web\UnauthorizedHttpException;

class UnAuthorization extends UnauthorizedHttpException{
    public function __construct($message){
        $this->message = $message;
    }
} 