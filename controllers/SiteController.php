<?php

namespace app\controllers;

use app\helpers\ApiHelper;
use app\helpers\AuthUtils;
use app\helpers\Constants;
use app\helpers\MCrypt;
use app\helpers\MyCurl;
use app\helpers\UserHelper;
use app\models\Category;
use app\models\Content;
use app\models\ListCategory;
use app\models\ListComments;
use app\models\ListContents;
use app\models\LoginForm;
use app\models\RegisterForm;
use phpCAS;
use Yii;
use yii\base\Exception;
use yii\caching\ApcCache;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Url;

class SiteController extends BaseController
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
                            //'verify-token'
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

    public function actionSearch() {
        $params = [];
        $params = [];
        $pagination = '';
        $params['listContent'] = null;
        $typeLoad = $this->getParameter('typeLoad', '');//1:load more home, 2: load more
        $page = $this->getParameter('page', 1);//current page
        $keyword = $this->getParameter('keyword', '');//loc
        $search = $this->getParameter('search','');
        if(!empty($keyword)) {
            $_COOKIE['keyword'] = $keyword;;
        }else if(strpos($search,'/site/search') !== false) {
            return $this->redirect('/site/index',302);
        }else{
            return $this->redirect($search,302);
        }

        try {
            //get list content
            $response = ApiHelper::apiQuery([
                ApiHelper::API_SONG,
                'filter' => 0,
                'per-page' => Constants::_PER_PAGE_LIST,
                'page' => $page,
                'keyword' => $keyword
            ], null, false);

            if(ApiHelper::isResultSuccess($response)) {
                if(!empty($response['data']['items'])) {

                    $params['listContent'] = new ListContents($response['data']);
                    $pagination = new Pagination(['totalCount'=>$response['data']['_meta']['totalCount']]);
                    $count = $response['data']['_meta']['totalCount'];
                    $pagination->pageSize = $response['data']['_meta']['perPage'];
                }
            }
        } catch (Exception $e) {
        }

        if(empty($typeLoad)) {
            return $this->render("search", $params);
        }
        return $this->renderPartial("_search", $params);
    }

    /**
     * list comments
     */
    public function actionListComments() {
        $contentId = $this->getParameter('contentId');
        $type = $this->getParameter('type');
        $page = $this->getParameter('page');
        try {
            $response = ApiHelper::apiQuery([
                ApiHelper::API_CONTENT_COMMENTS,
                'content_id'=> $contentId,
                'type' => $type,
                'page' => $page,
                'per-page' => Constants::_PER_PAGE_HOME
            ], null, false);
            if(ApiHelper::isResultSuccess($response)) {
                $listComments = new ListComments($response['data']);
                return $this->renderPartial('_list_comment', ['listComments' => $listComments,'type'=> $type]);
            }
        } catch(Exception $e) {
        }
    }

    /**
     * feedback
     * @return string
     */
    public function actionFeedback(){
        if(UserHelper::isGuest()) {

        }
        $contentId = $this->getParameterPost('contentId');
        $type = $this->getParameterPost('type');
        $content = $this->getParameterPost('content');
        $rating = $this->getParameterPost('rating');
        if(null == $content || '' == trim($content)) {
            return Json::encode(['success'=>false, 'message' => 'Không thành công. Qúy khách vui lòng nhập lời bình.']);
        }

        if(!is_numeric($contentId) || $contentId <= 0) {
            return Json::encode(['success'=>false, 'message' => 'Nội dung không hợp lệ']);
        }
        try {
            $response = ApiHelper::apiQuery(
                [ApiHelper::API_CONTENT_FEEDBACK],
                ['content_id' => $contentId,
                    'content' => $content,
                    'rating'=>$rating
                ],
                false);
            if(ApiHelper::isResultSuccess($response)) {
                $message = !empty($response['message']) ? $response['message'] : 'Bình luận thành công.';
                return Json::encode(['success'=>true, 'message' => $message]);
            }
            $message = !empty($response['message']) ? $response['message'] : 'Bình luận không thành công.';
            return Json::encode(['success'=>false, 'message' => $message,'data'=>'']);
        }catch (Exception $e) {
            return Json::encode(['success'=>false, 'message' => 'Không thành công. Qúy khách vui lòng thử lại sau ít phút.','data'=>'']);
        }
    }

    /**
     * chi tiet truyen hinh
     *
     * @param $id
     * @return string
     */
    public function actionLive($id) {
        //get live detail
        $live = null;
        $relatedContent = null;
        $type = $this->getParameter('type', 0);
        $cateId = $this->getParameter('cateId', 0);
        $cateName = $this->getParameter('cateName', '');
        try {
            $response = ApiHelper::apiQuery([
                ApiHelper::API_CONTENT_DETAIL,
                'id' => $id,
            ], null, false);
            if(ApiHelper::isResultSuccess($response)) {
                if(!empty($response['data'])) {
                    $live = new Content($response['data']);
                }
            }
        } catch (Exception $e) {
        }

        //get related live
        try {
            $response = ApiHelper::apiQuery([
                ApiHelper::API_CONTENT_RELATED,
                'id' => $id,
                'per-page' => Constants::_PER_PAGE_LIST_RELATED
            ], null, false);
            if(ApiHelper::isResultSuccess($response)) {
                if(!empty($response['data']['items'])) {
                    $relatedContent = new ListContents($response['data']);
                }
            }
        } catch (Exception $e) {
        }
        //get list comments
        $listComments = null;
        try {
            $response = ApiHelper::apiQuery([
                ApiHelper::API_CONTENT_COMMENTS, 'content_id'=> $id,'type' => 'comment'
            ], null, false);
            if(ApiHelper::isResultSuccess($response)) {
                $listComments = new ListComments($response['data']);
            }
        } catch(Exception $e) {}
        //xly back url
        if(Constants::_TYPE_SEARCH == $type) {
            $this->backUrl = Url::toRoute(['search', 'keyword'=> isset($_COOKIE['keyword']) && !empty($_COOKIE['keyword']) ? $_COOKIE['keyword']:'']);
        } else if(!empty($type) && !empty($cateId)) {
            $type = $live->type;
            $this->backUrl = Url::toRoute(['list-content','cateId'=> $cateId ,'cateName'=> $cateName, 'type'=> $type]);
        } else {
            $type = $live->type;
            $this->backUrl = Url::toRoute(['category','type'=> $type]);
        }

        return $this->render("live", ['live' => $live,
            'relatedContent' => $relatedContent,
            'listComments' => $listComments,
        ]);
    }

    /**
     * chi tiet noi dung video
     *
     * @param int $id
     * @return string
     */
    public function actionVideo($id=0) {
        //get content detail
        $content = null;
        $relatedContent = null;
        $listContent = null;
        $type = $this->getParameter('type', 0);
        $cateId = $this->getParameter('cateId', 0);
        $cateName = $this->getParameter('cateName', '');


        //get list film drama
        try {
            $response = ApiHelper::apiQuery([
                ApiHelper::API_FILM_DRAMA_DETAIL,
                'id' => $id,
            ], null, false);
            if(ApiHelper::isResultSuccess($response)) {
                if(!empty($response['data']['items'])) {
                    $listContent= new ListContents($response['data']);
                }
            }
        } catch (Exception $e) {
        }

        try {
            $response = ApiHelper::apiQuery([
                ApiHelper::API_CONTENT_DETAIL,
                'id' => $id,
            ], null, false);
            if(ApiHelper::isResultSuccess($response)) {
                if(!empty($response['data'])) {
                    $content= new Content($response['data']);
                }
            }
        } catch (Exception $e) {
        }

        //get related content
        try {

            $response = ApiHelper::apiQuery([
                ApiHelper::API_CONTENT_RELATED,
                'id' => $id,
                'per-page' => Constants::_PER_PAGE_LIST_RELATED
            ], null, false);
            if(ApiHelper::isResultSuccess($response)) {
                if(!empty($response['data']['items'])) {
                    $relatedContent = new ListContents($response['data']);
                }
            }
        } catch (Exception $e) {
        }

        //get list comments
        $listComments = null;
        try {
            $response = ApiHelper::apiQuery([
                ApiHelper::API_CONTENT_COMMENTS,
                'content_id'=> $id,
                'type' => 'comment',
                'per-page'=> Constants::_PER_PAGE_HOME
            ], null, false);
            if(ApiHelper::isResultSuccess($response)) {
                $listComments = new ListComments($response['data']);
            }
        } catch(Exception $e) {}

        //xly back url
        if(Constants::_TYPE_SEARCH == $type) {
            $this->backUrl = Url::toRoute(['search', 'keyword'=> isset($_COOKIE['keyword']) && !empty($_COOKIE['keyword']) ? $_COOKIE['keyword']:'']);
        } else if(!empty($type) && !empty($cateId)) {
            $type = $content->type;
            $this->backUrl = Url::toRoute(['list-content','cateId'=> $cateId ,'cateName'=> $cateName, 'type'=> $type]);
        } else {
            //var_dump($type);exit;
            //$type = $content->type;
            $type = 2;
            $this->backUrl = Url::toRoute(['category','type'=> $type]);
        }

        return $this->render("content_detail", [
            'content' => $content,
            'relatedContent' => $relatedContent,
            'listComments' => $listComments,
            'listContent'=> $listContent
        ]);
    }

    /**
     * hien thi danh sach noi dung theo category
     */
    public function actionListContent() {
        $params = [];
        $params = [];
        $params['listContent'] = null;
        $params['type'] = null;
        $typeLoad = $this->getParameter('typeLoad');//1:load more home, 2: load more
        $page = $this->getParameter('page', 1);//current page
        $type = $this->getParameter('type');//content type: video, news,live
        $filter = $this->getParameter('filter', Constants::_FILTER_MOST_VIEW);//loc
        $cateId = $this->getParameter('cateId');//danh muc chua' noi dung
        $cateName = $this->getParameter('cateName');//danh muc chua' noi dung
        $category = new Category(['id' => $cateId,'display_name' => $cateName, 'type'=> $type]);
        $params['category'] = $category;
        try {
            //get list content
            $response = ApiHelper::apiQuery([
                ApiHelper::API_CONTENT_BY_CATEGORY,
                'filter' => 0,
                'type' => $type,
                'category' => $cateId,
                'per-page' => Constants::_PER_PAGE_LIST,
                'page' => $page
            ], null, false);

            if(ApiHelper::isResultSuccess($response)) {
                if(!empty($response['data']['items'])) {
                    $params['listContent'] = new ListContents($response['data']);
                }
            }
        } catch (Exception $e) {
        }

        //xly back
        switch($type) {
            case Constants::_TYPE_MUSIC:
                $this->backUrl = Url::toRoute(['category','type'=> Constants::_TYPE_MUSIC]);
                break;
            case Constants::_TYPE_CLIP:
                $this->backUrl = Url::toRoute(['category','type'=> Constants::_TYPE_CLIP]);
                break;
            case Constants::_TYPE_FILM:
                $this->backUrl = Url::toRoute(['category','type'=> Constants::_TYPE_FILM]);
                break;
            case Constants::_TYPE_LIVE:
                $this->backUrl = Url::toRoute(['category','type'=> Constants::_TYPE_LIVE]);
                break;
            default:
        }
        return $this->render("list_content", $params);
    }

    /**
     * hien thi danh sach noi dung theo category
     *
     */
    public function actionCategory() {
        $params = [];
        $params['categories'] = null;
        $type = $this->getParameter('type', 1);//current page
        $id = $this->getParameter('id',null);
        try {
            $response = ApiHelper::apiQuery([
                ApiHelper::API_CATEGORY_BY_CATEGORY,
                'type' => $type,
                'id'=>$id
            ], null, false);
            if(ApiHelper::isResultSuccess($response)) {

                if(!empty($response['data']['categories'])) {
                    $params['categories'] = new ListCategory($response['data']);
//                    var_dump($params);exit;
                }
            }
        } catch (Exception $e) {
        }
        $this->backUrl = Url::toRoute(['index']);

        return $this->render("category", $params);
    }
    /**
     * load ajax
     *
     * @return string
     */
    public function actionLoadList() {
        $params = [];
        $params['listContent'] = null;
        $params['type'] = null;
        $page = $this->getParameter('page', 1);//current page
        $type = $this->getParameter('type');//content type: video, news,live
        $filter = $this->getParameter('filter');//loc
        $category = $this->getParameter('category');//danh muc chua' noi dung
        $typeLoad = $this->getParameter('typeLoad');//1:load more home, 2: load more
        $order = $this->getParameter('order');//1:load more home, 2: load more
        $url_api = [];
        switch($typeLoad) {
            case Constants::_AJAX_LOAD_HOME:
                $url_api = [
                    ApiHelper::API_CONTENT_BY_CATEGORY,
                    'filter' => $filter,
                    'type' => $type,
                    'category' => $category,
                    'per-page' => Constants::_PER_PAGE_HOME,
                    'page' => $page,
                    'order' => $order,
                ];
                break;
            case Constants::_AJAX_LOAD_RELATED:
                $id = $this->getParameter('id', 0);
                $url_api = [
                    ApiHelper::API_CONTENT_RELATED,
                    'filter' => $filter,
                    'type' => $type,
                    'id' => $id,
                    'category' => $category,
                    'per-page' => Constants::_PER_PAGE_LIST_RELATED,
                    'page' => $page,
                    'order' => $order
                ];
                break;
            case Constants::_AJAX_LOAD_LIST:
            default:
                $url_api = [
                    ApiHelper::API_CONTENT_BY_CATEGORY,
                    'filter' => $filter,
                    'type' => $type,
                    'category' => $category,
                    'per-page' => Constants::_PER_PAGE_LIST,
                    'page' => $page,
                ];
                break;
        }

        //get list content
        try {
            $response = ApiHelper::apiQuery($url_api, null, false);

            if(ApiHelper::isResultSuccess($response)) {
                if(!empty($response['data']['items'])) {
                    $params['listContent'] = new ListContents($response['data']);
                }
            }
        } catch (Exception $e) {
        }
        $params['type'] = $type;
        return $this->renderPartial("_list_content", $params);
    }

    /**
     * home page
     * @return string
     */
    public function actionIndex()
    {
        $params = [];
        $params['listMusic'] = null;
        $params['listSao'] = null;
        $params['listFilm'] = null;
        $params['listLive'] = null;
        $params['listnhipsongtre'] = null;
        $params['listgiaoduc'] = null;
        $params['listthethao'] = null;
        $params['listthoitrang'] = null;
        $params['listgame'] = null;

        try{
            $response = ApiHelper::apiQuery([
                ApiHelper::API_HOME,
                'filter' => Constants::_FILTER_HOT,
                'type' => Constants::_TYPE_MUSIC,
                'per-page' => Constants::_PER_PAGE_HOME,
                'order'=> 0//newest
            ], null, false);
            if(ApiHelper::isResultSuccess($response)) {
                if(!empty($response['data'])) {
                    $params['listMusic'] = new ListContents($response['data']['music']);
                    $params['listSao'] = new ListContents($response['data']['sao']);
                    $params['listFilm'] = new ListContents($response['data']['film']);
                    $params['listLive'] = new ListContents($response['data']['live']);
                    $params['listnhipsongtre'] = new ListContents($response['data']['nhipsongtre']);
                    $params['listthethao'] = new ListContents($response['data']['thethao']);
                    $params['listthoitrang'] = new ListContents($response['data']['thoitrang']);
                    $params['listgame'] = new ListContents($response['data']['game']);
                    $params['listgiaoduc'] = new ListContents($response['data']['giaoduc']);
                }
            }

        } catch(Exception $e){

        }
        /*//get music
        try {

            $response = ApiHelper::apiQuery([
                ApiHelper::API_CONTENT_BY_CATEGORY,
                'filter' => Constants::_FILTER_HOT,
                'type' => Constants::_TYPE_MUSIC,
                'per-page' => Constants::_PER_PAGE_HOME,
                'order'=> 0
            ], null, false);
            if(ApiHelper::isResultSuccess($response)) {
                if(!empty($response['data']['items'])) {
                    $params['listMusic'] = new ListContents($response['data']);
                }
            }
        } catch (Exception $e) {
        }

        //get clips
        try {

            $response = ApiHelper::apiQuery([
                ApiHelper::API_CONTENT_BY_CATEGORY,
                'filter' => Constants::_FILTER_HOT,
                'type' => Constants::_TYPE_CLIP,
                'per-page' => Constants::_PER_PAGE_HOME,
                'order'=> 0
            ], null, false);
            if(ApiHelper::isResultSuccess($response)) {
                if(!empty($response['data']['items'])) {
                    $params['listClips'] = new ListContents($response['data']);
                }
            }
        } catch (Exception $e) {

        }
        //get film
        try {

            $response = ApiHelper::apiQuery([
                ApiHelper::API_CONTENT_BY_CATEGORY,
                'filter' => Constants::_FILTER_HOT,
                'type' => Constants::_TYPE_FILM,
                'per-page' => Constants::_PER_PAGE_HOME,
                'order'=> 0
            ], null, false);
            if(ApiHelper::isResultSuccess($response)) {
                if(!empty($response['data']['items'])) {
                    $params['listFilm'] = new ListContents($response['data']);
                }
            }
        } catch (Exception $e) {
        }
        //get live vtv
        try {

            $response = ApiHelper::apiQuery([
                ApiHelper::API_CONTENT_BY_CATEGORY,
                'filter' => Constants::_FILTER_HOT,
                'type' => Constants::_TYPE_LIVE,
                'per-page' => Constants::_PER_PAGE_HOME,
                'order'=> 0
            ], null, false);
            if(ApiHelper::isResultSuccess($response)) {
                if(!empty($response['data']['items'])) {
                    $params['listLive'] = new ListContents($response['data']);
                }
            }

        } catch (Exception $e) {

        }*/
        return $this->render('index', $params);
    }

    /**
     * verify token
     *
     * @param $code
     * @return string
     */
    /*public function actionVerifyToken($code) {
        if(Yii::$app->request->post()) {
            $code = Yii::$app->request->post(['code']);
            $response = ApiHelper::apiQuery([ApiHelper::API_VERIFY,'code' => $code], null,false);
            if(ApiHelper::isResultSuccess($response)) {

            }
        }
        return $this->render('verify_token', [
            'code' => $code,
        ]);
    }*/

    /**
     * dang ky tai khoan
     *
     * @return string
     */
    public function actionGetPassword()
    {
        if (!UserHelper::isGuest()) {
            return $this->goHome();
        }
        try {
            $model = new RegisterForm();
            if (Yii::$app->request->post() && $model->load(Yii::$app->request->post())) {
                $response = $model->register();
                return Json::encode(['success' => $response['success'],'message'=> $response['message']]);
            }
        } catch(Exception $e) {
            return Json::encode(['success' => false,'message'=> 'Có lỗi xảy ra']);
        }


    }


    /**
     * action login
     *
     * @return string|\yii\web\Response
     */
    /*public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->get()) && $model->validate()) {
            $response = $model->login();
            if (ApiHelper::isResultSuccess($response)) {
                UserHelper::login($response);
                return Json::encode(['success' => true,'message'=> $response['message']]);
            } else {
                return Json::encode(['success' => false,'message'=> $response['message']]);
            }
        }
        return Json::encode(['success' => false,'message'=> 'Có lỗi xảy ra']);
    }*/

    public function actionLogin($data, $time) {
        $data = MCrypt::decrypt($data);
        $data = explode("-", $data);

        $msisdn = "";
        if (sizeof($data) == 2 && $data[1] == $time) {
            $msisdn = $data[0];
        }Yii::info($msisdn);
        echo "msisdn: " . $msisdn;
    }

    /**
     * redirect qua login: /frontend/web/index.php?r=gw/login&return_url={callback}
     * {callback phai URL encode}
     * sau khi login thanh cong se redirect lai callback, kem them 2 tham so 'data' va 'time': &data={data}&time=1423205085
     * sample: return_url tro den action test-login cua controller nay
     * @param $return_url
     */
    public function actionSsoLogin(){
        $return_url = Yii::$app->request->get('return_url',null);
        $session = Yii::$app->session;

        if ($msisdn = $session->has("MSISDN")) {
            UserHelper::setMsisdn($session->get("MSISDN"));
        }
        else {
//            if($msisdn = AuthUtils::casAuth()){
            if(true){
//                UserHelper::setMsisdn($msisdn);
                UserHelper::setMsisdn("841279587456");
                UserHelper::setCasLogin(true);
            }
        }

       /* $time = time();
        $data = MCrypt::encrypt($msisdn."-".$time);
        $return_url .= "&data=$data&time=$time";*/
        if(!empty($return_url)) {
            $this->redirect($return_url);
            return;
        }
        $this->redirect('index');
    }

    /**
     * xu ly log out
     *
     * @param $url
     */
    public function actionAfterLogout($url) {
        $this->redirect($url);
    }

    /**
     * redirect qua logout: /frontend/web/index.php?r=gw/logout&return_url={callback}
     * {callback phai URL encode}
     * sau khi logout thanh cong se redirect lai callback, ko them tham so
     * sample: return_url tro ve action test-logout cua controller nay
     * @param $return_url
     */
    public function actionCasLogout($return_url=null){
        UserHelper::removeCasLogin();
        UserHelper::removeMsisdn();
        $domain = Yii::$app->params['domain'];
        AuthUtils::casLogout($domain);
    }

   /* public function actionTest() {
        $url = 'https://sieusaongoaihang.vn/restservice/ssnh/api.php?api_user_id=vtvdigital&api_key=2dc535de05ea32d59dc250bb904cda8a&fun=xu_ly_binh_chon&sms_id=802&sdt=84977309223&mdv=EPL&content=ARS&time=1438033680';
        $curl = new MyCurl();
        $curl->headers['Accept'] = 'application/json';

        $res = $curl->get($url);
        var_dump(file_get_contents($url));
        var_dump($res);exit;
    }*/

}
