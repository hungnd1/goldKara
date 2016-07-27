<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/26/15
 * Time: 11:02 AM
 */

namespace app\widgets;


use app\helpers\ApiHelper;
use app\models\ListCategory;
use yii\base\Exception;
use yii\base\Widget;

class Header extends Widget {
    const _FILTER = 0;
    public static $category = null;
    public $route;
    public $id;
    public function init(){
        //$this->route = \Yii::$app->controller->route;
        $this->route = \Yii::$app->request->get('type', 0);
        $this->id = \Yii::$app->request->get('id',0);
        try {
           $response = ApiHelper::apiQuery([ApiHelper::API_CATEGORY],null, false);
            if(ApiHelper::isResultSuccess($response)) {
                if(!empty($response['data'])) {
                    self::$category = new ListCategory($response['data']);
                }
            }
        } catch(Exception $e) {}
    }

    public function run() {
        return $this->render('//Header/header',['route'=> $this->route,'id'=>$this->id,'category'=>self::$category]);
    }
} 