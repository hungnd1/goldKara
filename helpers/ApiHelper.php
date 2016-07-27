<?php
/**
 * Created by PhpStorm.
 * User: thuc
 * Date: 10/17/14
 * Time: 2:47 PM
 */

namespace app\helpers;

use app\models\SetFbUserInfoFormForm;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use yii\web\UrlManager;

class ApiHelper
{
    /*********************************************************
     * CONSTANTS
     *********************************************************/
    const CONTENT_NAME = 'Nội dung';
    /*********************************************************
     * FILTER
     *********************************************************/
    const FILTER_ALL = 'all';
    const FILTER_NEWEST = 'newest';
    const _PAGING = 20;//20 BAN GHI TREN 1 TRANG

    /********************************************************
     * API
     */
    const API_GET_MSISDN = 'user/get-msisdn';
    const API_LOGIN = 'user/login';
    const API_LOGOUT = 'user/logout';
    const API_REGISTER = 'user/register';
    const API_USER_INFO = 'user/user-info';
    const API_lIST_PACKAGE = 'service/service-group';
    const API_USER_BUY_PACKAGE = 'subscriber/purchase-service-package';
    const API_TRANSACTION_ID = 'subscriber/transaction-id';

    const API_USER_CANCEL_PACKAGE = 'subscriber/cancel-service-package';
    const API_USER_CHANGE_INFO = 'user/edit-profile';
    const API_USER_CHANGE_PACKAGE = 'subscriber/change-package';
    const API_VERIFY = 'user/verify';
    const API_ALL_CATEGORY = 'category/index';
    const API_CATEGORY_DETAIL = 'category/detail';
    const API_CONTENT_DETAIL = 'content/detail';
    const API_CONTENT_RELATED = 'content/related';
    const API_CONTENT_FEEDBACK= 'content/comment';
    const API_CONTENT_COMMENTS= 'content/comments';
    const API_CONTENT_ADD_FAVORITE = 'content/favorite';
    const API_CONTENT_ADD_UN_FAVORITE = 'content/unfavorite';
    const API_USER_BUY_CONTENT = 'subscriber/purchase-content';
    const API_USER_TRANSACTION = 'subscriber/transaction';
    const API_USER_FAVORITE = 'subscriber/favorites';
    const API_CONTENT_PROVIDERS = 'app/content-provider';
    const API_DOWNLOAD_CONTENT = 'subscriber/download';
    const API_FILM_DRAMA_DETAIL ='content/film-drama-detail';
    const API_ADD_VIEW_COUNT ='content/add-view-count';
    const API_SAVE_CONTENT_VIEW ='content/save-content-view';

    /**
     * //content/list-content?type=0&category=0&filter=0&keyword
     *  type = 0 - all
                1 - video
                2 - live
                3 - music
                4 - news
     * filter 0 --> nothing
            1 --> featured
            2 --> hot
            3 --> especial
     */
    const API_SONG = 'songs';
    const API_SLIDE = 'homes-slides';
    const API_HOME = 'app/myvideo-home';


    /**
     * @param $apiResults - array (json decoded from api result)
     * @return bool
     */
    public static function isResultSuccess($apiResults) {
        return ($apiResults != null) && ($apiResults['success'] == true);
    }

    public static function getResultMessage($apiResults) {
        $message = "";
        if (isset($apiResults['message'])) {
            $message = $apiResults['message'];
        }
        else if (isset($apiResults['data']) && isset($apiResults['data']['message'])) {
            $message = $apiResults['data']['message'];
        }

        return $message;
    }

    /**
     * @param $email
     * @param $password
     * @return bool
     * @throws ServerErrorHttpException
     */
    public static function login($email, $password) {
        $res = ApiHelper::apiQuery([ApiHelper::API_LOGIN, 'email' => $email, 'password' => $password], null, false);
        if (ApiHelper::isResultSuccess($res)) {
            UserHelper::login($res);
            return true;
        }
        return false;
    }


    /**
     * @param null $phone
     * @return bool
     * @throws ServerErrorHttpException
     */
    public static function register($phone = null) {
        return ApiHelper::apiQuery([ApiHelper::API_REGISTER], ['phone_number' => $phone], false);
    }

    /**
     * @return bool
     * @throws ServerErrorHttpException
     */
    public static function logout() {
        Yii::error("logout");
        UserHelper::logout();
        $res = ApiHelper::apiQuery([ApiHelper::API_LOGOUT], null, false);
        return true;
    }

    /**
     * @param string|array $params use a string to represent a route (e.g. `site/index`),
     * or an array to represent a route with query parameters (e.g. `['site/index', 'param1' => 'value1']`).
     * @param $postParams
     *   - null if GET method is used,
     *   - array of POST params (or empty array - []) if POST method is used
     * @return mixed | array
     */
    public static function apiQuery($params, $postParams = null, $throwExceptionOnFailure = false) {
        /* @var $urlManager UrlManager */
        $urlManager = Yii::$app->urlManagerApi;

        $url = $urlManager->createAbsoluteUrl($params);
        Yii::info("API Url: " . $url);
        Yii::info("Post param: ");
        Yii::info($postParams);

        $ch = new MyCurl();
        $ch->headers['Accept'] = 'application/json';

        //msisdn
        if(!UserHelper::isGuest()) {
            $ch->headers['msisdn'] = UserHelper::getMsisdn();
        }

        $access = UserHelper::getAccessToken();
        if($access) {
            //$params['access_token'] = urlencode($access);
            $ch->headers['Authorization'] = 'Bearer ' . $access;
        }
        //add api key
        $apiKey = Yii::$app->params['client_api_key'];
        if(!empty($apiKey)) {
            $ch->headers['X-Kara-Api-Key'] = $apiKey;
        }
        //add secret key
        $secretKey = Yii::$app->params['crypt_key'];
        if(!empty($secretKey)) {
            $ch->headers['X-Kara-Secret-Key'] = $secretKey;
        }
        $results = null;
        if ($postParams === null) {
            $response = $ch->get($url);
        }
        else {
            $response = $ch->post($url, $postParams);
        }
        Yii::info('response:');
        Yii::info($response);
        if(!empty($response)) {
            try {
                $results = Json::decode($response->body);
            }
            catch (InvalidParamException $ex) {
                return['success'=>false,'data'=> ['message' => 'Xảy ra lỗi hệ thống, vui lòng thử lại sau: ']];
                //throw new ServerErrorHttpException("Xảy ra lỗi hệ thống, vui lòng thử lại sau: ");
            }

            if ($throwExceptionOnFailure && !ApiHelper::isResultSuccess($results)) {
                return['success'=>false,'data'=> ['message' => 'Xảy ra lỗi khi gọi API']];
                //throw new ServerErrorHttpException("Xảy ra lỗi khi gọi API: ");
            }
        }
        else {
            return['success'=>false,'data'=> ['message' => 'Xảy ra lỗi hệ thống, vui lòng thử lại sau: ']];
            //throw new ServerErrorHttpException("Xảy ra lỗi hệ thống, vui lòng thử lại sau");
        }

        return $results;
    }

    public static function fbLogin($fbToken)
    {
        $res = ApiHelper::apiQuery([ApiHelper::ROUTE_FB_LOGIN, 'fbToken' => urlencode($fbToken)], null, true);
        if (ApiHelper::isResultSuccess($res)) {
            UserHelper::login($res);
        }
        return $res;
    }

}
