<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/17/15
 * Time: 11:16 AM
 */

namespace app\models;


use yii\base\Model;

class ServicePackage extends Model
{
    public $id;
    public $name;
    public $display_name;
    public $price;
    public $period;
    public $description;
    public $message_notice;
    public $is_my_package;

    public function __construct($data){
        parent::setAttributes($data, false);
    }
} 