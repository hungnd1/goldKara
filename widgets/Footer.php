<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/26/15
 * Time: 9:33 AM
 */

namespace app\widgets;


use app\helpers\ApiHelper;
use yii\base\Exception;
use yii\base\Widget;

class Footer extends Widget{
    public static $listProvider = [];
    public function run() {
        $listComments = null;
        try {
            $response = ApiHelper::apiQuery([
                ApiHelper::API_CONTENT_PROVIDERS,
            ], null, false);
            if(ApiHelper::isResultSuccess($response)) {
                self::$listProvider = $response['data'];
            }
        } catch(Exception $e) {}

        return $this->render("//Footer/footer", ['listProvider'=> self::$listProvider]);
    }
} 