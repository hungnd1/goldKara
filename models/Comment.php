<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/26/15
 * Time: 9:51 AM
 */

namespace app\models;


use yii\base\Model;

class Comment extends Model {

    public $id;
    public $img_user;
    public $short;
    public $content;
    public $msisdn;
    public $create_date;

    public function __construct($data){
        parent::setAttributes($data, false);
    }
} 