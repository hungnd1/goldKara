<?php
/**
 * Description of AuthUtils
 *
 * @author Tran Bac Son
 */

namespace app\helpers;

use Yii;
use phpCAS;
use yii\helpers\Url;
use yii\web\Session;

class AuthUtils {

    public static function casAuth(){
        /*
        $cas_host = 'vinaphone.com.vn/auth';
        $cas_port = 443;
        $cas_context = '/';
        $cas_real_hosts = array (
            'vinaphone.com.vn'
        );
        $vinaphone_auth_url = 'https://vinaphone.com.vn/auth/login?service=';
        $local_auth_url = 'http://cus.ibox2.dev/';
         */
        $cas_host = Yii::$app->params['cas_host'];
        $cas_port = Yii::$app->params['cas_port'];
        $cas_context = Yii::$app->params['cas_context'];
        /*$cas_real_hosts = Yii::$app->params['cas_real_hosts'];
        $vinaphone_auth_url = Yii::$app->params['vinaphone_auth_url'];*/

        phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context, false);
        phpCAS::setNoCasServerValidation();
        phpCAS::forceAuthentication();

        if (phpCAS::isAuthenticated()) {
            $msisdn = phpCAS::getUser();
            return $msisdn;
        }
        return null;
    }

    public static function casLogout($url=null){
        //self::casAuth();
        $session = Yii::$app->session;
        $session->remove('MSISDN');
        $session->remove('CAS');
        $session->remove('phpCAS');
        $cas_host = Yii::$app->params['cas_host'];
        $cas_port = Yii::$app->params['cas_port'];
        $cas_context = Yii::$app->params['cas_context'];

        phpCAS::client(CAS_VERSION_2_0, $cas_host, $cas_port, $cas_context, false);
        phpCAS::setNoCasServerValidation();
        phpCAS::logout(['service' => $url]);
    }
}
