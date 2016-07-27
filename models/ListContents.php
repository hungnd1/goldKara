<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/26/15
 * Time: 10:37 AM
 */

namespace app\models;


use yii\base\Model;
use yii\data\Pagination;

class ListContents extends Model
{
    public $items;
    public $_meta;
    public $contents;
    public $service;

    public function __construct($data)
    {
        parent::setAttributes($data, false);
        $this->items = [];
        if (!empty($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $content = new Content($item);
                array_push($this->items, $content);
            }
        } else if(empty($data['items']) && !empty($this->contents) && is_array($this->contents)) {
            if(!empty($this->contents) && is_array($this->contents)) {
                $this->items = [];
                foreach ($this->contents as $item) {
                    $content = new Content($item);
                    array_push($this->items, $content);
                }
                $this->contents = null;
            }
        }
        //panigation
        if(!empty($data['_meta'])){
            $pagination = new Pagination(['totalCount' => $data['_meta']['totalCount']]);
            $pagination->pageSize = $data['_meta']['perPage'];
        }
        //service
        if(!empty($this->service) && is_array($this->service)) {
            $service = new ServicePackage($this->service);
            $this->service = $service;
        }
    }

    public function setListContent($contents){
        if(!empty($contents) && is_array($contents)) {
            $this->items = [];
            foreach ($contents as $item) {
                $content = new Content($item);
                array_push($this->items, $content);
            }
        }
    }
} 