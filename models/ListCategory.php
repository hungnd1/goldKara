<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/28/15
 * Time: 3:36 PM
 */

namespace app\models;


use yii\base\Model;

class ListCategory extends Model
{
    const TYPE_FILM = 1;
    const TYPE_LIVE = 2;
    const TYPE_MUSIC = 3;
    const TYPE_NEWS = 4;
    const TYPE_CLIP = 5;

    public $categories;
    public $items;


    public function __construct($data)
    {
        parent::setAttributes($data, false);
        $this->items = [];
        foreach ($this->categories as $items) {
            /* @var $category \app\models\Category */
            $category = new Category($items);
            array_push($this->items , $category);
        }
    }
} 