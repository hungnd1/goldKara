<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/21/15
 * Time: 3:38 PM
 */

namespace app\controllers;


use app\helpers\ApiHelper;
use app\helpers\Constants;
use app\helpers\UserHelper;
use app\models\ListContents;
use app\models\ListGroupPackages;
use app\models\ListTransactions;
use app\models\UnAuthorization;
use app\models\User;
use Faker\Provider\cs_CZ\DateTime;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\BaseUrl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\UnauthorizedHttpException;

class UserController extends BaseController
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => [
                            'get-password',
                            'info',
                            'transactions',
                            'favorites',
                            'buy-odd',
                            'add-favorite',
                            'cancel-package',
                            'purchase-package',
                            'change-info',
                            'change-password',
                            'download',
                            'change-purchase-package',
                            'un-favorite',
                            'view-count'
                        ],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    //'register' => ['post'],
                    //'verify-token' => ['post'],
                ],
            ],
        ];
    }
 public function actionUnFavorite() {
        $contentId = $this->getParameterPost('contentId', 0);
        if(!is_numeric($contentId) || $contentId <= 0){
            return Json::encode(['success'=> false, 'message'=>'Nội dung không hợp lệ.']);
        }
        try {
            $response = ApiHelper::apiQuery([ApiHelper::API_CONTENT_ADD_UN_FAVORITE, 'content_id' => $contentId],null, false);
            if(ApiHelper::isResultSuccess($response)) {
                $message = !empty($response['message']) ? $response['message'] :'Qúy khách dã thêm thành công nội dung vào danh sách yêu thích';
            } else {
                $message = !empty($response['message']) ? $response['message'] : 'Không thành công. Qúy khách vui lòng thử lại sau ít phút.';
            }

            return  Json::encode(['success'=> $response['success'], 'message'=> $message]);
        } catch(Exception $e) {
            return  Json::encode(['success'=> false, 'message'=> 'Không thành công. Qúy khách vui lòng thử lại sau ít phút.']);
        }
    }
    public function actionDownload() {
        $contentId = $this->getParameterPost('contentId', 0);
        if(!is_numeric($contentId) || $contentId <= 0){
            return Json::encode(['success'=> false, 'message'=>'Nội dung không hợp lệ.']);
        }
        $url = '';
        try {
            $response = ApiHelper::apiQuery([ApiHelper::API_DOWNLOAD_CONTENT],['content_id' => $contentId,
                '']);
            if(ApiHelper::isResultSuccess($response)) {
                $message = !empty($response['message']) ? $response['message'] :'';
                $url = $response['data']['url_download'];
            } else {
                if ($response['message'] == 10) {
                    $message = 'Bạn không đủ tiền để thực hiện giao dịch này';
                }else if($response['message'] ==111){
                    $message = 'Link download đang bị lỗi! Quý khách có thể quay lại sau';
                } else{
                    $message = 'Giao dịch không thành công. Vui lòng thử lại sau ít phút. Xin cảm ơn!';
                }
            }

            return  Json::encode(['success'=> $response['success'], 'message'=> $message, 'url_download' => $url]);
        } catch(Exception $e) {
            return  Json::encode(['success'=> false, 'message'=> 'Giao dịch không thành công. Vui lòng thử lại sau ít phút. Xin cảm ơn!']);
        }
    }

    /**
     * danh sach noi dung dc yeu thich
     */
    public function actionFavorites() {
        $params = [];
        $params['listContent'] = null;
        $typeLoad = $this->getParameter('typeLoad', '');
        $page = $this->getParameter('page', '');
        try {
            $response = ApiHelper::apiQuery([
                ApiHelper::API_USER_FAVORITE,
                'page' => $page,
                'per-page' => Constants::_PER_PAGE_LIST
            ], null, false);

            if (ApiHelper::isResultSuccess($response)) {
                if(!empty($response['data']['items'])) {
                    $params['listContent'] = new ListContents($response['data']);
                }
            }
        } catch(Exception $e) {

        }

        if(empty($typeLoad)){
            return $this->render('favorites', $params);
        }
        return $this->renderPartial('_favorites', $params);
    }

    /**
     * down content
     *
     * @return string
     */
    public function actionBuyOdd() {
        $contentId = $this->getParameterPost('contentId', 0);

        if(!is_numeric($contentId) || $contentId <= 0){
            return Json::encode(['success'=> false, 'message'=>'Nội dung không hợp lệ.']);
        }
        try {
            $response = ApiHelper::apiQuery([ApiHelper::API_USER_BUY_CONTENT],['content_id' => $contentId, '']);
            if(ApiHelper::isResultSuccess($response)) {
                $message = !empty($response['message']) ? $response['message'] :'';

            } else {
                if ($response['message'] == 10) {
                    $message = 'Bạn không đủ tiền để thực hiện giao dịch này';
                }
                else{
                    $message = 'Giao dịch không thành công. Vui lòng thử lại sau ít phút. Xin cảm ơn!';
                    }
                }

            return  Json::encode(['success'=> $response['success'], 'message'=> $message]);
        } catch(Exception $e) {
            return  Json::encode(['success'=> false, 'message'=> 'Giao dịch không thành công. Vui lòng thử lại sau ít phút. Xin cảm ơn!']);
        }
    }


    /**
     * down content
     *
     * @return string
     */
    public function actionViewCount() {
        $contentId = $this->getParameterPost('contentId', 0);

        if(!is_numeric($contentId) || $contentId <= 0){
            return Json::encode(['success'=> false, 'message'=>'Nội dung không hợp lệ.']);
        }
        $countView = $this->getParameterPost('countView',0);
        if(!is_numeric($countView) || $countView < 0){
            return Json::encode(['success'=> false, 'message'=>'Nội dung không hợp lệ.']);
        }
        try {
            $response = ApiHelper::apiQuery([ApiHelper::API_ADD_VIEW_COUNT],['content_id' => $contentId, 'count_view'=>$countView]);
            if(ApiHelper::isResultSuccess($response)) {
                $message = !empty($response['message']) ? $response['message'] :'Qúy khách đã thêm thành công nội dung vào danh sách yêu thích';
            } else {
                $message = !empty($response['message']) ? $response['message'] : 'Không thành công. Qúy khách vui lòng thử lại sau ít phút.';
            }

            return  Json::encode(['success'=> $response['success'], 'message'=> $message]);
        } catch(Exception $e) {
            return  Json::encode(['success'=> false, 'message'=> 'Không thành công. Qúy khách vui lòng thử lại sau ít phút.']);
        }
    }

    public function actionViewContent() {
        $contentId = $this->getParameterPost('content_id', 0);
        $service_provider_id = $this->getParameterPost('service_provider_id',0);
        $content_provider_id = $this->getParameterPost('content_provider_id',0);
        $type = $this->getParameterPost('type',0);
        if(!is_numeric($contentId) || $contentId <= 0){
            return Json::encode(['success'=> false, 'message'=>'Nội dung không hợp lệ.']);
        }
        try {
            $response = ApiHelper::apiQuery([ApiHelper::API_SAVE_CONTENT_VIEW],['content_id' => $contentId, 'service_provider_id'=>$service_provider_id,'content_provider_id'=>$content_provider_id,'type'=>$type,'user'=>UserHelper::getMsisdn()]);
            if(ApiHelper::isResultSuccess($response)) {
                $message = !empty($response['message']) ? $response['message'] :'Thanh cong';
            } else {
                $message = !empty($response['message']) ? $response['message'] : 'Không thành công';
            }

            return  Json::encode(['success'=> $response['success'], 'message'=> $message]);
        } catch(Exception $e) {
            return  Json::encode(['success'=> false, 'message'=> 'Không thành công. Qúy khách vui lòng thử lại sau ít phút.']);
        }
    }
    /**
     *
     * @return string
     */
    public function actionAddFavorite() {
        $contentId = $this->getParameterPost('contentId', 0);
        if(!is_numeric($contentId) || $contentId <= 0){
            return Json::encode(['success'=> false, 'message'=>'Nội dung không hợp lệ.']);
        }
        try {
            $response = ApiHelper::apiQuery([ApiHelper::API_CONTENT_ADD_FAVORITE, 'content_id' => $contentId],null, false);
            if(ApiHelper::isResultSuccess($response)) {
               $message = !empty($response['message']) ? $response['message'] :'Qúy khách đã thêm thành công nội dung vào danh sách yêu thích';
            } else {
                $message = !empty($response['message']) ? $response['message'] : 'Không thành công. Qúy khách vui lòng thử lại sau ít phút.';
            }

            return  Json::encode(['success'=> $response['success'], 'message'=> $message]);
        } catch(Exception $e) {
            return  Json::encode(['success'=> false, 'message'=> 'Không thành công. Qúy khách vui lòng thử lại sau ít phút.']);
        }
    }


    /**
     * huy goi dich vu
     * @return json
     */
    public function actionCancelPackage() {
        $packageId = $this->getParameter('id', 0);//id cua goi cuoc
        if(UserHelper::isGuest()) {//chua login
            return Json::encode(['success'=> false, 'message' => 'Qúy khách cần đăng nhập để sử dụng tính năng này.']);
        }
        
        if (is_numeric($packageId) && $packageId > 0) {
            try {
//                $response = ApiHelper::apiQuery(
//                    [ApiHelper::API_USER_CANCEL_PACKAGE,],
//                    ['channel' => Constants::CHANNEL_TYPE_WAP,
//                    'service_id' => $packageId],
//                    false);
//                if (ApiHelper::isResultSuccess($response)) {
//                    $message = !empty($response['message']) ? $response['message'] : 'Quý khách đã hủy gói cước thành công.';
//                    return Json::encode(['success'=> true, 'message' => $message]);
//                }
//                $message = !empty($response['message']) ? $response['message'] : 'Quý khách đã hủy gói cước không thành công.';
//                return Json::encode(['success'=> false, 'message' => $message]);
                $response = ApiHelper::apiQuery(
                    [ApiHelper::API_TRANSACTION_ID],
                    ['channel' => Constants::CHANNEL_TYPE_WAP,
                        'service_id' => $packageId],
                    false);
                if (ApiHelper::isResultSuccess($response)) {
                    $request_id = $response['data']['request_id'];
                    $package_name = $response['data']['package_name'];
                    $returnUrl = "http://vplus.vinaphone.com.vn/user/packages";
                    $backUrl = "http://vplus.vinaphone.com.vn/user/packages";
                    $key = $response['data']['securecode'];
                    $cp=$response['data']['cp'];
                    $service=$response['data']['service'];
                    $request_datetime = date("YmdHis");
                    $channel = 'WEB';
                    $language='vi';
                    $clean_data = $request_id.$returnUrl.$backUrl.$cp.$service.$package_name.$request_datetime.$channel.$key;
                    //echo 'Clean:'.$clean_data.'|';
                    $securecode = md5($request_id.$returnUrl.$backUrl.$cp.$service.$package_name.$request_datetime.$channel.$key);
                    //echo 'HASH:'.$securecode.'|';
                    $url = "http://dk.vinaphone.com.vn/unreg.jsp?requestid=".$request_id."&returnurl=".$returnUrl."&backurl=".$backUrl.
                        "&cp=".$cp."&service=".$service."&package=".$package_name."&requestdatetime=".$request_datetime."&channel=".$channel."&securecode=".$securecode."&language=".$language;
//                    $message = !empty($response['message']) ? $response['message'] : 'Đăng ký gói cước thành công!';
                    return Json::encode(['success'=> true, 'message' => $url]);
//                    return $this->redirect($url, 200);
                }
                $message = !empty($response['message']) ? $response['message'] : 'Quý khách đã hủy gói cước không thành công.';
                return Json::encode(['success'=> false, 'message' => $message]);
            } catch (Exception $e) {
                return Json::encode(['success'=> false, 'message' => 'Có lỗi xảy ra, xin vui lòng thử lại sau.']);
            }
        }
        return Json::encode(['success'=> false, 'message' => 'Gói cước không hợp lệ.']);

    }

    /**
     * mua goi cuoc
     *
     * @return json
     */
//    public function actionPurchasePackage() {
//        $packageId = $this->getParameter('id', 0);//id cua goi cuoc
//        if(UserHelper::isGuest()) {//chua login
//            return Json::encode(['success'=> false, 'message' => 'Qúy khách cần đăng nhập để sử dụng tính năng này.']);
//        }
//        if (is_numeric($packageId) && $packageId > 0) {
//            try {
//                $response = ApiHelper::apiQuery(
//                    [ApiHelper::API_USER_BUY_PACKAGE],
//                    ['channel' => Constants::CHANNEL_TYPE_WAP,
//                    'service_id' => $packageId],
//                    false);
//                if (ApiHelper::isResultSuccess($response)) {
//                    $message = !empty($response['message']) ? $response['message'] : 'Đăng ký gói cước thành công!';
//                    return Json::encode(['success'=> true, 'message' => $message]);
//                }
//                $message = !empty($response['message']) ? $response['message'] : 'Đăng ký gói cước không thành công!';
//                return Json::encode(['success'=> false, 'message' => $message]);
//            } catch (Exception $e) {
//                return Json::encode(['success'=> false, 'message' => 'Có lỗi xảy ra, xin vui lòng thử lại sau.']);
//            }
//        }
//        return Json::encode(['success'=> false, 'message' => 'Gói cước không hợp lệ.']);
//    }
    public function actionPurchasePackage() {

        $packageId = $this->getParameter('id', 0);//id cua goi cuoc
        if(UserHelper::isGuest()) {//chua login
            return Json::encode(['success'=> false, 'message' => 'Qúy khách cần đăng nhập để sử dụng tính năng này.']);
        }
        if (is_numeric($packageId) && $packageId > 0) {
            try {
                $response = ApiHelper::apiQuery(
                    [ApiHelper::API_TRANSACTION_ID],
                    ['channel' => Constants::CHANNEL_TYPE_WAP,
                        'service_id' => $packageId],
                    false);
                if (ApiHelper::isResultSuccess($response)) {
                    $request_id = $response['data']['request_id'];
                    $package_name = $response['data']['package_name'];
                    $returnUrl = "http://vplus.vinaphone.com.vn/user/packages";
                    $backUrl = "http://vplus.vinaphone.com.vn/user/packages";
                    $key = $response['data']['securecode'];
                    $cp=$response['data']['cp'];
                    $service=$response['data']['service'];
                    $request_datetime = date("YmdHis");
                    $channel = 'WEB';
                    $language='vi';
                    $clean_data = $request_id.$returnUrl.$backUrl.$cp.$service.$package_name.$request_datetime.$channel.$key;
                    //echo 'Clean:'.$clean_data.'|';
                    $securecode = md5($request_id.$returnUrl.$backUrl.$cp.$service.$package_name.$request_datetime.$channel.$key);
                    //echo 'HASH:'.$securecode.'|';
                    $url = "http://dk.vinaphone.com.vn/reg.jsp?requestid=".$request_id."&returnurl=".$returnUrl."&backurl=".$backUrl.
                        "&cp=".$cp."&service=".$service."&package=".$package_name."&requestdatetime=".$request_datetime."&channel=".$channel."&securecode=".$securecode."&language=".$language;
//                    $message = !empty($response['message']) ? $response['message'] : 'Đăng ký gói cước thành công!';
                    return Json::encode(['success'=> true, 'message' => $url]);
//                    return $this->redirect($url, 200);
                }
                $message = !empty($response['message']) ? $response['message'] : 'Đăng ký gói cước không thành công!';
                return Json::encode(['success'=> false, 'message' => $message]);
            } catch (Exception $e) {
                return Json::encode(['success'=> false, 'message' => 'Có lỗi xảy ra, xin vui lòng thử lại sau.']);
            }
        }
        return Json::encode(['success'=> false, 'message' => 'Gói cước không hợp lệ.']);
    }
    /**
     * mua goi cuoc
     *
     * @return json
     */
    public function actionChangePurchasePackage() {
        $packageId = $this->getParameter('id', 0);//id cua goi cuoc
        if(UserHelper::isGuest()) {//chua login
            return Json::encode(['success'=> false, 'message' => 'Qúy khách cần đăng nhập để sử dụng tính năng này.']);
        }
        if (is_numeric($packageId) && $packageId > 0) {
            try {
                $response = ApiHelper::apiQuery(
                    [ApiHelper::API_USER_CHANGE_PACKAGE],
                    ['channel' => Constants::CHANNEL_TYPE_WAP,
                    'service_id' => $packageId],
                    false);
                if (ApiHelper::isResultSuccess($response)) {
                    $message = !empty($response['message']) ? $response['message'] : 'Đăng ký gói cước thành công!';
                    return Json::encode(['success'=> true, 'message' => $message]);
                }
                $message = !empty($response['message']) ? $response['message'] : 'Đăng ký gói cước không thành công!';
                return Json::encode(['success'=> false, 'message' => $message]);
            } catch (Exception $e) {
                return Json::encode(['success'=> false, 'message' => 'Có lỗi xảy ra, xin vui lòng thử lại sau.']);
            }
        }
        return Json::encode(['success'=> false, 'message' => 'Gói cước không hợp lệ.']);
    }

    /**
     * list package/ buy packages
     * @return string
     */
    public function actionPackages()
    {
        $params = [];
        $packageId = $this->getParameter('id', 0);
        try {
            if (is_numeric($packageId) && $packageId > 0) {
                //todo thuc hien mua goi
                try {
                    $response = ApiHelper::apiQuery([
                        ApiHelper::API_USER_BUY_PACKAGE,
                        'channel' => Constants::CHANNEL_TYPE_WAP,
                        'service_id' => $packageId
                    ], null, false);
                    if (ApiHelper::isResultSuccess($response)) {
                        if (!empty($response['data'])) {

                        }
                    }
                } catch (Exception $e) {
                }
            }
            //todo list danh sach goi cuoc
            try {
                $response = ApiHelper::apiQuery([
                    ApiHelper::API_lIST_PACKAGE
                ], null, false);

                if (ApiHelper::isResultSuccess($response)) {
                    if (!empty($response['data'])) {
                        $params['listGroupPackages'] = new ListGroupPackages($response);
                    }
                }
            } catch (Exception $e) {
            }
        } catch (Exception $e) {

        }
        //xly back url
        $this->backUrl = Url::toRoute('info');
        return $this->render('packages', $params);
    }

    /**
     * history transactions
     *
     * @return string
     */
    public function actionTransactions()
    {
        $params = [];
        $params['transactions'] = null;
        $typeLoad = (int)($this->getParameter('typeLoad', 0));
        $page = $this->getParameter('page');
        $perPage = $this->getParameter('per-page', Constants::_PER_PAGE_HOME);
        try {
            $response = ApiHelper::apiQuery([
                ApiHelper::API_USER_TRANSACTION,
                'page' => $page,
                'per-page' => $perPage
            ], null, false);

            if (ApiHelper::isResultSuccess($response)) {
                if (!empty($response['data'])) {
                    $params['transactions'] = new ListTransactions($response);
                }
            }

        } catch (Exception $e) {
        }
        //xly back url
        $this->backUrl = Url::toRoute('info');
        if ($typeLoad == 0) {
            return $this->render('transaction', $params);
        } else {
            return $this->renderPartial('_transactions', $params);
        }

    }

    /**
     * user info
     * @return string
     */
    public function actionInfo()
    {
        $params = [];
        $params['user'] = null;
        //get userInfo
        try {
            $response = ApiHelper::apiQuery([
                ApiHelper::API_USER_INFO
            ], null, false);
            if (ApiHelper::isResultSuccess($response)) {
                if (!empty($response['data'])) {
                    $params['user'] = new User($response['data']);
                }
            }
        } catch (Exception $e) {
        }
        $this->backUrl = \Yii::$app->request->referrer;
        return $this->render("info", $params);
    }

    /**
     * change user info
     * @return string|\yii\web\Response
     */
    public function actionChangeInfo()
    {
        $now = new \DateTime('now');
        $month = $now->format('m');
        $year = $now->format('Y');
        $day = $now->format('d');

        $user = null;
        try {
            //get user detail
            $response = ApiHelper::apiQuery([
                ApiHelper::API_USER_INFO,
            ], null, false);
            if (ApiHelper::isResultSuccess($response)) {
                $user = new User($response['data']);
                $birthday = explode('/', date('d/m/Y', $user->birthday));
                $user->day = $birthday[0];
                $user->month = $birthday[1];
                $user->year = $birthday[2];
            } else {
                return $this->goHome();
            }
            $message = [];
            $message['success'] = false;
            //process submit form
            if (\Yii::$app->request->post() && $user->load(\Yii::$app->request->post())) {
                if ($user->validate()) {
                    //update date
                    if(empty($user->full_name) || $user->full_name == ''){
                        $message['message'] = 'Họ và tên không được để trống';
                    }else if(empty($user->email) || $user->email == ''){
                        $message['message'] = 'Email không được để trống';
                    }else if(!filter_var($user->email,FILTER_VALIDATE_EMAIL)){
                        $message['message'] = 'Email không đúng địng dạng, quý khách vui lòng nhập lại email';
                    }else if($user->day >= $day && $user->month == $month && $user->year == $year || $user->month > $month && $user->year == $year || $user->year > $year){
                        $message['message'] = 'Ngày sinh không hợp lệ';
                    } else {
                        $user->birthday = strtotime(str_replace('/', '-', $user->day .'/' . $user->month . '/' . $user->year));
                        //call api update user info
                        $response = ApiHelper::apiQuery([ApiHelper::API_USER_CHANGE_INFO], [
                            'full_name' => $user->full_name,
                            'birthday' => $user->birthday,
                            'email' => $user->email
                        ], false);
                        if (ApiHelper::isResultSuccess($response)) {
                            $message['success'] = true;
                        }
                        $message['message'] = $response['message'];
                    }
                } else {
                    $message['message'] = $user->getFirstErrors();
                }
            }
        } catch (Exception $e) {
            $message['message'] = 'Có lỗi xảy ra, xin vui lòng thử lại sau.';
        }
        //xly back url
        $this->backUrl = Url::toRoute('info');
        return $this->render('change-info', ['user' => $user, 'message'=> $message]);
    }

    /**
     * reset passwordt
     */
    public function actionChangePassword()
    {
        try {

        } catch (Exception $e) {

        }
    }

    public function actionLogout() {
        try {
            $response = ApiHelper::apiQuery([ApiHelper::API_LOGOUT], null, false);
            UserHelper::logout();
        } catch(Exception $e) {
        }
        return $this->redirect(['site/index']);
    }
} 