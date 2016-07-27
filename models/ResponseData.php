<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/26/15
 * Time: 10:28 AM
 */

namespace app\models;


use yii\base\Model;

class ResponseData extends Model{
    public $message;
    public $status;
    public $data = [];
    public function __construct($response){
        parent::setAttributes($response);
    }
} 