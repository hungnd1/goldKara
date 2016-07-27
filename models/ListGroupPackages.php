<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/17/15
 * Time: 11:22 AM
 */

namespace app\models;


use yii\base\Model;

class ListGroupPackages extends Model{
    public $data;
    public $items;

    public function __construct($data) {
        parent::setAttributes($data, false);
        if(!empty($this->data)) {
            $this->items = [];
            foreach($this->data as $group) {
                $gr = new GroupPackages($group);
                array_push($this->items, $gr);
            }
            $this->data = null;
        }
    }
} 