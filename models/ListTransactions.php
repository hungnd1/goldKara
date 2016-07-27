<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/9/15
 * Time: 6:06 PM
 */

namespace app\models;


use yii\base\Model;

class ListTransactions extends Model{
    public $data;
    public $items;
    public $_meta;

    public function __construct($data){
        parent::setAttributes($data, false);
        if(!empty($this->data) && is_array($this->data)) {
            $this->items = [];
            foreach($this->data['items'] as $item) {
                $transaction = new Transaction($item);
                array_push($this->items, $transaction);
            }
            $this->_meta = $this->data['_meta'];
            $this->data = null;
        }
    }
} 