<?php
use app\helpers\CConfig;
use app\helpers\Constants;
use app\helpers\UserHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<nav class="navbar navbar-default nav-new">
    <div class="container-fluid padding-tablet">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?php if (!empty(Yii::$app->controller->backUrl)) { ?><a class="bt-back"
                                                                     href="<?= Yii::$app->controller->backUrl; ?>"><span
                        class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a><?php } ?>
            <a class="navbar-brand" href="<?= Url::toRoute(['site/index']) ?>"><img class="logo"
                                                                                    src="<?= Yii::$app->request->baseUrl ?>/advance/images/logo.png"
                                                                                    alt=""></a>
        </div>
        <div id="navbar" class="navbar-collapse nav-edit collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown" <?= $route == Constants::_TYPE_MUSIC && $id == 0 ? 'class="active"' : '' ?>><a class="thea"
                        href="<?= Url::toRoute(['site/category', 'type' => Constants::_TYPE_MUSIC]) ?>"><img
                            src="<?= Yii::$app->request->baseUrl ?>/advance/images/ic-music.png"><br>THỂ LOẠI</a>
                    <div class="dropdown-content">
                        <a href="#">Nhạc Trẻ</a>
                        <a href="#">Nhạc Thiếu Nhi</a>
                        <a href="#">Nhạc Trữ Tình</a>
                        <a href="#">Nhạc Cách Mạng</a>
                        <a href="#">Nhạc Âu Mỹ</a>
                        <a href="#">Nhạc Hàn Quốc</a>
                        <a href="#">Nhạc Trung Quốc</a>
                        <a href="#">Nhạc Campuchia</a>
                        <a href="#">Nhạc Nhạc Quê Hương</a>
                        <a href="#">Nhạc Dance</a>
                    </div>
                </li>
                <li <?= $route == Constants::_TYPE_FILM && $id == 0 ?  'class="active"' : '' ?>><div class="thea"><a
                        href="<?= Url::toRoute(['site/category', 'type' => Constants::_TYPE_FILM]) ?>"><img
                            src="<?= Yii::$app->request->baseUrl ?>/advance/images/ic-film.png"><br>TOP BÀI HÁT</a></div>
                </li>
                <li<?= $route == Constants::_TYPE_CLIP && $id == 62 ? ' class="active"' : '' ?>><a
                        href="<?= Url::toRoute(['site/category', 'type' => Constants::_TYPE_CLIP,'id'=>CConfig::NHIP_SONG_TRE]) ?>"><img
                            src="<?= Yii::$app->request->baseUrl ?>/advance/images/ic-nst.png"><br>TOP BÀI THU</a
                    ></li>
                <li <?= $route == Constants::_TYPE_CLIP && $id == 63 ? ' class="active"' : '' ?>><a
                        href="<?= Url::toRoute(['site/category', 'type' => Constants::_TYPE_CLIP,'id'=>CConfig::THE_THAO]) ?>"><img
                            src="<?= Yii::$app->request->baseUrl ?>/advance/images/ic-sport.png"><br>HƯỚNG DẪN</a></li>
                <li>

                    <?php $form = ActiveForm::begin([
                        'action' => Url::toRoute('site/search'),
                        'method' => 'GET',
                        'id' => 'login-form',
                        'options' => ['class' => 'navbar-form search-form']
                    ]); ?>

                    <input type="text" class="form-control" name="keyword" placeholder="Search..."
                           value="<?= isset($_COOKIE['keyword']) && !empty($_COOKIE['keyword']) ? $_COOKIE['keyword'] : ''; ?>">
                    <input type="hidden" name="search"
                           value="<?php echo str_replace(".php", "", "$_SERVER[REQUEST_URI]"); ?>">
                    <button type="submit" class="btn btn-primary bt-search"><span class="glyphicon glyphicon-search"
                                                                                  aria-hidden="true"></span>
                    </button>
                    <?php ActiveForm::end(); ?>
                </li>
            </ul>

            <!--   Start Login         -->
            <?php
//                        var_dump(\app\helpers\UserHelper::isGuest());exit;
            if (\app\helpers\UserHelper::isGuest()) {
                ?>
                <div class="top-account">
                    <a class="navbar-right login-click"
                       href="<?= \yii\helpers\Url::toRoute(['site/sso-login', 'return_url' => '/site/index']) ?>">ĐĂNG
                        NHẬP</a>
                    <!-- Modal -->
                    <!--                <div class="modal fade" id="formLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"-->
                    <!--                     aria-hidden="true">-->
                    <!--                    <div class="modal-dialog login-modal">-->
                    <!--                        <div class="modal-content">-->
                    <!--                            <div class="modal-header">-->
                    <!--                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span-->
                    <!--                                        aria-hidden="true">&times;</span></button>-->
                    <!--                                <h4 class="modal-title" id="myModalLabel">ĐĂNG NHẬP</h4>-->
                    <!--                            </div>-->
                    <!--                            <div class="modal-body">-->
                    <!--                                <div class="input-group">-->
                    <!--                                    <span class="input-group-addon" id="basic-addon1"><span-->
                    <!--                                            class="glyphicon glyphicon-user" aria-hidden="true"></span></span>-->
                    <!---->
                    <!--                                    <input type="text" class="form-control" placeholder="Tài khoản"-->
                    <!--                                           aria-describedby="basic-addon1">-->
                    <!--                                </div>-->
                    <!--                                <div class="input-group mr-login">-->
                    <!--                                    <span class="input-group-addon" id="basic-addon1"><span-->
                    <!--                                            class="glyphicon glyphicon-lock" aria-hidden="true"></span></span>-->
                    <!--                                    <input type="password" class="form-control" placeholder="Mật khẩu"-->
                    <!--                                           aria-describedby="basic-addon1">-->
                    <!--                                </div>-->
                    <!--                            </div>-->
                    <!--                            <div class="modal-footer">-->
                    <!--                                <button type="button" class="bt-login">ĐĂNG NHẬP</button>-->
                    <!--                                <a href="">Quên mật khẩu?</a>-->
                    <!--                            </div>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                    <!--                </div>-->

                </div>
            <?php } else { ?>
                <div class="top-account">
                    <a class="navbar-right login-click"><?= UserHelper::getMsisdn() ?><span class="caret"></span></a>
                    <ul class="drop-menu">
                        <li><a href="<?= Url::toRoute(['user/info']) ?>"><span class="glyphicon glyphicon-user"
                                                                               aria-hidden="true"></span>Thông tin cá
                                nhân</a></li>
                        <li><a href="<?= Url::toRoute(['user/transactions']) ?>"><span
                                    class="glyphicon glyphicon-dashboard" aria-hidden="true"></span>Lịch sử giao
                                dịch</a></li>
                        <li><a href="<?= Url::toRoute(['user/favorites']) ?>"><span
                                    class="glyphicon glyphicon-dashboard" aria-hidden="true"></span>Danh sách yêu thích</a>
                        </li>
                        <li><a href="<?= Url::toRoute(['user/packages']) ?>"><span class="glyphicon glyphicon-gift"
                                                                                   aria-hidden="true"></span>Mua gói</a>
                        </li>
                        <?php if (!Yii::$app->controller->is3G) { ?>
                            <li>
                                <a href="<?= Url::toRoute(['site/cas-logout', 'return_url' => 'site/index']) ?>"><span
                                        class="glyphicon glyphicon-share" aria-hidden="true"></span>Đăng xuất</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>

            <?php } ?>
        </div><!--/.nav-collapse -->
    </div>
</nav>
<div class="container-fluid padding-none">