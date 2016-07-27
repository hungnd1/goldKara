<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/20/15
 * Time: 5:37 PM
 */

namespace app\controllers;

use app\helpers\ApiHelper;
use app\helpers\Crypt;
use app\helpers\CUtils;
use app\helpers\UserHelper;
use app\helpers\VNPHelper;
use Detection\MobileDetect;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\HtmlPurifier;
use yii\web\Controller;
use Yii;

class BaseController extends Controller{
    public $enableCsrfValidation = false;
    const _PAGING_ADVANCE = 20;
    const _PAGING_BASIC = 10;
    const DEVICE_ANDROID_MOBILE = 4;
    const DEVICE_IOS_MOBILE = 3;
    const DEVICE_BASIC_MOBILE = 2;
    const DEVICE_PC = 5;
    const CHANNEL_TYPE_WAP = 1;

    var $msisdn;
    var $backUrl;
    var $crypt;
    public $is3G = false;
    var $purifier;
    var $detect;
    public $detector_mobile = null;
    public $paging = self::_PAGING_ADVANCE;
    public $deviceType;
    public $channelType;
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                /*'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        //'roles' => ['@'],
                    ],
                ],*/
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post','get'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function __construct($id, $module) {
        parent::__construct($id, $module);
        $ip3G = Yii::$app->params['ip3G'];
        $this->channelType = self::CHANNEL_TYPE_WAP;
        $this->detect = new \Mobile_Detect();
        if ($this->detect->isMobile()) {
            if ($this->detect->isTablet() || $this->detect->is('AndroidOS')) {
                $this->deviceType = self::DEVICE_ANDROID_MOBILE;
            } else if($this->detect->is('iOS')){
                $this->deviceType = self::DEVICE_IOS_MOBILE;
            } else {
                if ($this->detect->mobileGrade() === 'A') {
                    $this->deviceType = self::DEVICE_ANDROID_MOBILE;
                } else {
                    $this->deviceType = self::DEVICE_ANDROID_MOBILE;
                }
            }
        } else {
            $this->deviceType = self::DEVICE_PC;
        }
        $clientIP = Yii::$app->getRequest()->getUserIP();
        //$clientIP = CUtils::getUserIP();
        //Detect access via 3G?
        $F5IPPattern = '(^(10)(\\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}$)';
        $F5I2 = '(^(113\\.185\\.)([1-9]|1[0-9]|2[0-9]|3[0-1])(\\.([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])))';
        $WAPGWIPPattern = '(^172.16.30.1[1-2]$)';
        $WAPGWIPPattern2 = '(113.185.0.16)';
        if(preg_match($F5IPPattern,$clientIP)) {
            $this->is3G = true;
        } else if(preg_match($F5I2,$clientIP)) {
            $this->is3G = true;
        } else if(preg_match($WAPGWIPPattern2,$clientIP)) {
            $this->is3G = true;
        } else if(preg_match($WAPGWIPPattern,$clientIP)) {
            $this->is3G = true;
        } else {
            $this->is3G = false;
        }
        $msisdn = VNPHelper::getMsisdn();
        $typeFormat = 0;
        foreach ($ip3G as $ip) {
            if (CUtils::cidrMatch($clientIP, $ip)) {
                $this->is3G = true;
                break;
            }
        }
        $access_token = '';
        $msisdn_mobile = UserHelper::getMsisdn() != null ? UserHelper::getMsisdn():'';
//        UserHelper::setMsisdn('841279587456');
        $this->detector_mobile = new \Mobile_Detect();
        if($this->detector_mobile->isMobile()){
            if($this->is3G) {

                if(CUtils::validateMobile($msisdn,$typeFormat)){
                    $this->msisdn = $msisdn;
                    UserHelper::setMsisdn($this->msisdn);
                    /*try {
                        $response = ApiHelper::apiQuery([ApiHelper::API_GET_MSISDN],null, false);
                        if(ApiHelper::isResultSuccess($response)) {
                            UserHelper::login($response);
                        }
                    } catch(Exception $e) {
                    }*/
                } else {
                    UserHelper::logout();
                }
            } else if (!$this->is3G && $access_token == '' && $msisdn_mobile != ''&& !UserHelper::getCasLogin()) {
                Yii::$app->session->remove(UserHelper::SESSION_ACCESS_TOKEN);
                Yii::$app->session->remove(UserHelper::SESSION_MSISDN);
//                Yii::$app->session->destroy();
            }else{

            }
        }


        $this->purifier = new HtmlPurifier();
        $this->purifier->options = array('URI.AllowedSchemes'=>array(
            'http' => true,
            'https' => true,
        ));

        // crypt object
        $cryptOptions = array(
            'mode' => 'ecb',
            'algorithm' => 'blowfish',
            'base64' => true,
        );
        $secretKey = Yii::$app->params['crypt_key'];
        $cryptOptions['key'] = $secretKey . '_' . $this->msisdn . "_" . Yii::$app->request->getUserHost() ;//Yii::$app->request->getUserHostAddress();
        $this->crypt = new Crypt($cryptOptions);
    }

    /**
     * get value of parameter
     *
     * @param $param_name
     * @param null $default
     * @return mixed
     */
    public function getParameter($param_name, $default = null) {
        return \Yii::$app->request->get($param_name, $default);
    }

    /**
     * get value of parameter
     *
     * @param $param_name
     * @param null $default
     * @return mixed
     */
    public function getParameterPost($param_name, $default = null) {
        return \Yii::$app->request->post($param_name, $default);
    }
} 