<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/28/15
 * Time: 3:36 PM
 */

namespace app\models;


use yii\base\Model;

class ListComments extends Model
{
    public $items;
    public $_meta;

    public function __construct($data)
    {
        parent::setAttributes($data, false);
        $this->items = [];
        foreach ($data['items'] as $item) {
            $comment = new Comment($item);
            array_push($this->items, $comment);
        }
    }
} 