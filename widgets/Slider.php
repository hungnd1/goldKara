<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/26/15
 * Time: 9:22 AM
 */

namespace app\widgets;


use app\helpers\ApiHelper;
use app\helpers\Constants;
use app\models\Content;
use app\models\ListContents;
use yii\base\Exception;
use yii\base\Widget;

class Slider extends Widget{
    const _FILTER = 1;//slider
    const _PER_PAGE = 5;//slider
    public static $listSlideContent = null;

    public function init() {
        try {
            $response = ApiHelper::apiQuery([
                ApiHelper::API_CONTENT_BY_CATEGORY,
                'filter' => Constants::_FILTER_FEATURE,
                'type' => Constants::_TYPE_FILM,
                'per-page' => self::_PER_PAGE
            ], null, false);;
            if(ApiHelper::isResultSuccess($response)) {
                if(!empty($response['data']['items'])) {
                    self::$listSlideContent = new ListContents($response['data']);
                }
            }
        } catch(Exception $e) {
        }
    }
    public function run(){
        return $this->render("//Slider/slide", ['listSlideContent'=>self::$listSlideContent]);
    }
} 