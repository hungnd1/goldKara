<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/17/15
 * Time: 11:32 AM
 */

namespace app\models;


use yii\base\Model;

class GroupPackages extends Model{
    public $id;
    public $name;
    public $display_name;
    public $groupIcon;
    public $icon;
    public $description;
    public $services;
    public $items;

    public function __construct($data){
        parent::setAttributes($data, false);
        if(!empty($this->services)) {
            $this->items = [];
            foreach($this->services as $package) {
                $pk = new ServicePackage($package);
                array_push($this->items, $pk);
            }
        }
    }
} 