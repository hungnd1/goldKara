<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/28/15
 * Time: 3:31 PM
 */

namespace app\models;


use app\widgets\ContentItems;
use yii\base\Model;

class Category extends Model
{
    public $id;
    public $name;
    public $children;

    public function __construct($data){
        parent::setAttributes($data, false);
    }

} 