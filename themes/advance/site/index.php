<?php
use app\helpers\CConfig;
use app\helpers\Constants;
use app\helpers\UserHelper;
use app\widgets\ContentItems;
use app\widgets\Slider;
use yii\helpers\Url;

/* @var $listMusic \app\models\ListContents */
/* @var $listSao \app\models\ListContents */
/* @var $listFilm \app\models\ListContents */
/* @var $listLive \app\models\ListContents */
?>
<!-- Slider begin-->
<?= Slider::widget([]) ?>
<!-- Slider end-->
<div class="container padding-none">
    <!-- list app-->
    <div class="row margin-none">
        <!-- Modal buy confirm purchase-->
        <div class="modal fade" id="buy-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-gift"
                                                                        aria-hidden="true"></span><b id="text-buy">MUA
                                GÓI</b></h4>
                    </div>
                    <div class="modal-body" id="msg"></div>
                    <div class="modal-footer">
                        <input type="hidden" name="pk_id" id="pk_id">
                        <input type="hidden" name="type" id="type">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Để Sau</button>
                        <button type="button" class="btn btn-primary" onclick="acceptPurchase($(this));">ĐỒNG Ý</button>
                        <a id="notice-a" data-toggle="modal" data-target="#notice-modal" data-dismiss="modal"></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end Modal buy confirm -->
        <!-- Modal message notice-->

        <div class="modal fade" id="notice-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="margin-top: 100px">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"><span style="color: #FD7D12"
                                                                        class="glyphicon glyphicon-bell"
                                                                        aria-hidden="true"></span> <b>THÔNG BÁO</b></h4>
                    </div>
                    <div class="modal-body" id="msg2"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="ok()">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end Modal message notice -->
        <div class="list-video">
            <h4 class="title" id="music">ÂM NHẠC<a class="show-all"
                                                   href="<?= Url::toRoute(['site/category', 'type' => Constants::_TYPE_MUSIC]) ?>"><img
                        src="<?= Yii::$app->request->baseUrl ?>/advance/images/ic-show-all.png" alt=""></a>
                <a class="buy-package" onclick="muagoi();" data-toggle="modal">MUA GÓI</a>
                <span><a class="active" onclick="displayMusic('music-newest-1', $(this));">Mới nhất</a>  |  <a
                        onclick="displayMusic('music-newest-2', $(this));">Xem nhiều</a></span>
            </h4>

            <div class="clearfix"></div>
            <div class="dm-video" id="music-newest-1">
                <?php
                if (isset($listMusic) && !empty($listMusic->items)) {
                    foreach ($listMusic->items as $music) { ?>
                        <?= ContentItems::widget(['type' => Constants::_TYPE_MUSIC, 'content' => $music]) ?>
                    <?php }
                    ?>
                <?php } else { ?>
                    <span style="margin-left: 12px;color: red">Không có dữ liệu</span>
                <?php } ?>

            </div>
            <div class="dm-video" id="music-newest-2" style="display: none">


            </div>
        </div>

        <div class="clearfix"></div>
        <div class="list-video">
            <h4 class="title" id="clips">SAO+<a class="show-all"
                                                href="<?= Url::toRoute(['site/category', 'type' => Constants::_TYPE_CLIP,'id'=>CConfig::SAO]) ?>"><img
                        src="<?= Yii::$app->request->baseUrl ?>/advance/images/ic-show-all.png" alt=""></a>
                <a class="buy-package" onclick="muagoi();" data-toggle="modal">MUA GÓI</a>
                <span><a onclick="displayClips('clips-1', $(this));" class="active">Mới nhất</a>  |  <a
                        onclick="displayClips('clips-2', $(this));" class="">Xem nhiều</a></span>
            </h4>

            <div class="clearfix"></div>
            <div class="dm-video" id="clips-1">
                <?php
                if (isset($listSao) && !empty($listSao->items)) {
                    foreach ($listSao->items as $clips) { ?>
                        <?= ContentItems::widget(['type' => Constants::_TYPE_CLIP, 'content' => $clips]) ?>
                    <?php }
                    ?>
                <?php } else { ?>
                    <span style="margin-left: 12px;color: red">Không có dữ liệu</span>
                <?php } ?>
            </div>
            <div class="dm-video" id="clips-2" style="display: none"></div>
            <div class="clearfix"></div>
        </div>
        <div class="list-video">
            <h4 class="title" id="film">PHIM<a class="show-all"
                                               href="<?= Url::toRoute(['site/category', 'type' => Constants::_TYPE_FILM]) ?>"><img
                        src="<?= Yii::$app->request->baseUrl ?>/advance/images/ic-show-all.png" alt=""></a>
                <a class="buy-package" onclick="muagoi();" data-toggle="modal">MUA GÓI</a>
                    <span><a onclick="displayFilm('film-1', $(this));" class="active">Mới nhất</a>  |  <a
                            onclick="displayFilm('film-2', $(this));" class="">Xem nhiều</a></span>
            </h4>

            <div class="clearfix"></div>
            <div class="dm-video" id="film-1">
                <?php
                if (isset($listFilm) && !empty($listFilm->items)) {
                    foreach ($listFilm->items as $film) { ?>
                        <?= ContentItems::widget(['type' => Constants::_TYPE_FILM, 'content' => $film]) ?>
                    <?php }
                    ?>
                <?php } else { ?>
                    <span style="margin-left: 12px;color: red">Không có dữ liệu</span>
                <?php } ?>

            </div>
            <div class="dm-video" id="film-2" style="display: none"></div>

            <div class="clearfix"></div>
        </div>
        <div class="list-video">
            <h4 class="title" id="nhip">GIÁO DỤC<a class="show-all"
                                                        href="<?= Url::toRoute(['site/category', 'type' => Constants::_TYPE_CLIP,'id'=>CConfig::GIAO_DUC]) ?>"><img
                        src="<?= Yii::$app->request->baseUrl ?>/advance/images/ic-show-all.png" alt=""></a>
                <a class="buy-package" onclick="muagoi();" data-toggle="modal">MUA GÓI</a>
                    <span><a onclick="displayGiaoduc('giaoduc-1', $(this));" class="active">Mới nhất</a>  |  <a
                            onclick="displayGiaoduc('giaoduc-2', $(this));" class="">Xem nhiều</a></span>
            </h4>

            <div class="clearfix"></div>
            <div class="dm-video" id="giaoduc-1">
                <?php
                if (isset($listgiaoduc) && !empty($listgiaoduc->items)) {
                    foreach ($listgiaoduc->items as $clips) { ?>
                        <?= ContentItems::widget(['type' => Constants::_TYPE_CLIP, 'content' => $clips]) ?>
                    <?php }
                    ?>
                <?php } else { ?>
                    <span style="margin-left: 12px;color: red">Không có dữ liệu</span>
                <?php } ?>
            </div>
            <div class="dm-video" id="giaoduc-2" style="display: none"></div>

            <div class="clearfix"></div>
        </div>
        <div class="list-video">
            <h4 class="title" id="nhip">NHỊP SỐNG TRẺ<a class="show-all"
                                                        href="<?= Url::toRoute(['site/category', 'type' => Constants::_TYPE_CLIP,'id'=>CConfig::NHIP_SONG_TRE]) ?>"><img
                        src="<?= Yii::$app->request->baseUrl ?>/advance/images/ic-show-all.png" alt=""></a>
                <a class="buy-package" onclick="muagoi();" data-toggle="modal">MUA GÓI</a>
                    <span><a onclick="displayNhip('nhip-1', $(this));" class="active">Mới nhất</a>  |  <a
                            onclick="displayNhip('nhip-2', $(this));" class="">Xem nhiều</a></span>
            </h4>

            <div class="clearfix"></div>
            <div class="dm-video" id="nhip-1">
                <?php
                if (isset($listnhipsongtre) && !empty($listnhipsongtre->items)) {
                    foreach ($listnhipsongtre->items as $clips) { ?>
                        <?= ContentItems::widget(['type' => Constants::_TYPE_CLIP, 'content' => $clips]) ?>
                    <?php }
                    ?>
                <?php } else { ?>
                    <span style="margin-left: 12px;color: red">Không có dữ liệu</span>
                <?php } ?>
            </div>
            <div class="dm-video" id="nhip-2" style="display: none"></div>

            <div class="clearfix"></div>
        </div>
        <div class="list-video">
            <h4 class="title" id="thethao">THỂ THAO<a class="show-all"
                                                      href="<?= Url::toRoute(['site/category', 'type' => Constants::_TYPE_CLIP,'id'=>CConfig::THE_THAO]) ?>"><img
                        src="<?= Yii::$app->request->baseUrl ?>/advance/images/ic-show-all.png" alt=""></a>
                <a class="buy-package" onclick="muagoi();" data-toggle="modal">MUA GÓI</a>
                        <span><a onclick="displayTheThao('thethao-1', $(this));" class="active">Mới nhất</a>  |  <a
                                onclick="displayTheThao('thethao-2', $(this));" class="">Xem nhiều</a></span>
            </h4>

            <div class="clearfix"></div>
            <div class="dm-video" id="thethao-1">
                <?php
                if (isset($listthethao) && !empty($listthethao->items)) {
                    foreach ($listthethao->items as $clips) { ?>
                        <?= ContentItems::widget(['type' => Constants::_TYPE_CLIP, 'content' => $clips]) ?>
                    <?php }
                    ?>
                <?php } else { ?>
                    <span style="margin-left: 12px;color: red">Không có dữ liệu</span>
                <?php } ?>
            </div>
            <div class="dm-video" id="theothao-2" style="display: none"></div>

            <div class="clearfix"></div>
        </div>
        <div class="list-video">
            <h4 class="title" id="thoitrang">THỜI TRANG<a class="show-all"
                                                          href="<?= Url::toRoute(['site/category', 'type' => Constants::_TYPE_CLIP,'id'=>CConfig::THOI_TRANG]) ?>"><img
                        src="<?= Yii::$app->request->baseUrl ?>/advance/images/ic-show-all.png" alt=""></a>
                <a class="buy-package" onclick="muagoi();" data-toggle="modal">MUA GÓI</a>
                            <span><a onclick="displayThoitrang('thoitrang-1', $(this));" class="active">Mới nhất</a>  |  <a
                                    onclick="displayThoitrang('thoitrang-2', $(this));" class="">Xem nhiều</a></span>
            </h4>

            <div class="clearfix"></div>
            <div class="dm-video" id="thoitrang-1">
                <?php
                if (isset($listthoitrang) && !empty($listthoitrang->items)) {
                    foreach ($listthoitrang->items as $clips) { ?>
                        <?= ContentItems::widget(['type' => Constants::_TYPE_CLIP, 'content' => $clips]) ?>
                    <?php }
                    ?>
                <?php } else { ?>
                    <span style="margin-left: 12px;color: red">Không có dữ liệu</span>
                <?php } ?>
            </div>
            <div class="dm-video" id="thoitrang-2" style="display: none"></div>

            <div class="clearfix"></div>
        </div>
        <div class="list-video">
            <h4 class="title" id="game">GAME<a class="show-all"
                                               href="<?= Url::toRoute(['site/category', 'type' => Constants::_TYPE_CLIP,'id'=>CConfig::GAME]) ?>"><img
                        src="<?= Yii::$app->request->baseUrl ?>/advance/images/ic-show-all.png" alt=""></a>
                <a class="buy-package" onclick="muagoi();" data-toggle="modal">MUA GÓI</a>
                                <span><a onclick="displayGame('game-1', $(this));" class="active">Mới nhất</a>  |  <a
                                        onclick="displayGame('game-2', $(this));" class="">Xem nhiều</a></span>
            </h4>

            <div class="clearfix"></div>
            <div class="dm-video" id="game-1">
                <?php
                if (isset($listgame) && !empty($listgame->items)) {
                    foreach ($listgame->items as $clips) { ?>
                        <?= ContentItems::widget(['type' => Constants::_TYPE_CLIP, 'content' => $clips]) ?>
                    <?php }
                    ?>
                <?php } else { ?>
                    <span style="margin-left: 12px;color: red">Không có dữ liệu</span>
                <?php } ?>
            </div>
            <div class="dm-video" id="game-2" style="display: none"></div>

            <div class="clearfix"></div>
        </div>
    </div>
    <!-- end listapp -->

</div>
<!-- /container -->

<script type="text/javascript">
    function displayMusic(id, tagA) {
        $("h4#music a").each(function () {
            $(this).removeClass('active');
        });

        tagA.addClass("active");
        if (id == 'music-newest-1') {
            $('#music-newest-1').css('display', 'block');
            $('#music-newest-2').css('display', 'none');
            //music
            $('#' + id).html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>/advance/images/loading.gif'/>");
            var url = "<?= Url::toRoute(['load-list',
                'type' => Constants::_TYPE_MUSIC,
                'filter' => Constants::_FILTER_MOST_VIEW,
                'typeLoad' => Constants::_AJAX_LOAD_HOME,
                'order' => 0//order moi nhat
            ])?>";
            ajax(url, '#' + id);
        } else {
            $('#music-newest-1').css('display', 'none');
            $('#music-newest-2').css('display', 'block');
            $('#music-newest-2').html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
            var url = "<?= Url::toRoute(['load-list',
                'type' => Constants::_TYPE_MUSIC,
                'filter' => Constants::_FILTER_MOST_VIEW,
                'typeLoad' => Constants::_AJAX_LOAD_HOME,
                'order' => 1//order xem nhieu
            ])?>";
            ajax(url, '#' + id);
        }
    }

    function displayClips(id, tagA) {
        $("h4#clips a").each(function () {
            $(this).removeClass('active');
        });
        tagA.addClass("active");
        //clips
        if (id == 'clips-1') {
            $('#clips-1').css('display', 'block');
            $('#clips-2').css('display', 'none');
            $('#' + id).html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
            var url = "<?= Url::toRoute(['load-list',
                'type' => Constants::_TYPE_CLIP,
                'filter' => Constants::_FILTER_MOST_VIEW,
                'typeLoad' => Constants::_AJAX_LOAD_HOME,
                'category' => \app\helpers\CConfig::SAO,
                'order' => 0//order moi nhat
            ])?>";
            ajax(url, '#' + id);
        } else {
            $('#clips-2').css('display', 'block');
            $('#clips-1').css('display', 'none');
            $('#' + id).html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
            var url = "<?= Url::toRoute(['load-list',
                'type' => Constants::_TYPE_CLIP,
                'filter' => Constants::_FILTER_MOST_VIEW,
                'typeLoad' => Constants::_AJAX_LOAD_HOME,
                'category' => \app\helpers\CConfig::SAO,
                'order' => 1//order xem nhieu
            ])?>";
            ajax(url, '#' + id);
        }

    }

    function displayNhip(id, tagA) {
        $("h4#nhip a").each(function () {
            $(this).removeClass('active');
        });
        tagA.addClass("active");
        //clips
        if (id == 'nhip-1') {
            $('#nhip-1').css('display', 'block');
            $('#nhip-2').css('display', 'none');
            $('#' + id).html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
            var url = "<?= Url::toRoute(['load-list',
                'type' => Constants::_TYPE_CLIP,
                'filter' => Constants::_FILTER_MOST_VIEW,
                'typeLoad' => Constants::_AJAX_LOAD_HOME,
                'category' => \app\helpers\CConfig::NHIP_SONG_TRE,
                'order' => 0//order moi nhat
            ])?>";
            ajax(url, '#' + id);
        } else {
            $('#nhip-2').css('display', 'block');
            $('#nhip-1').css('display', 'none');
            $('#' + id).html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
            var url = "<?= Url::toRoute(['load-list',
                'type' => Constants::_TYPE_CLIP,
                'filter' => Constants::_FILTER_MOST_VIEW,
                'typeLoad' => Constants::_AJAX_LOAD_HOME,
                'category' => \app\helpers\CConfig::NHIP_SONG_TRE,
                'order' => 1//order xem nhieu
            ])?>";
            ajax(url, '#' + id);
        }

    }

    function displayTheThao(id, tagA) {
        $("h4#thethao a").each(function () {
            $(this).removeClass('active');
        });
        tagA.addClass("active");
        //clips
        if (id == 'thethao-1') {
            $('#thethao-1').css('display', 'block');
            $('#thethao-2').css('display', 'none');
            $('#' + id).html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
            var url = "<?= Url::toRoute(['load-list',
                'type' => Constants::_TYPE_CLIP,
                'filter' => Constants::_FILTER_MOST_VIEW,
                'typeLoad' => Constants::_AJAX_LOAD_HOME,
                'category' => \app\helpers\CConfig::THE_THAO,
                'order' => 0//order moi nhat
            ])?>";
            ajax(url, '#' + id);
        } else {
            $('#thethao-2').css('display', 'block');
            $('#thethao-1').css('display', 'none');
            $('#' + id).html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
            var url = "<?= Url::toRoute(['load-list',
                'type' => Constants::_TYPE_CLIP,
                'filter' => Constants::_FILTER_MOST_VIEW,
                'typeLoad' => Constants::_AJAX_LOAD_HOME,
                'category' => \app\helpers\CConfig::THE_THAO,
                'order' => 1//order xem nhieu
            ])?>";
            ajax(url, '#' + id);
        }

    }

    function displayGiaoduc(id, tagA) {
        $("h4#giaoduc a").each(function () {
            $(this).removeClass('active');
        });
        tagA.addClass("active");
        //clips
        if (id == 'giaoduc-1') {
            $('#giaoduc-1').css('display', 'block');
            $('#giaoduc-2').css('display', 'none');
            $('#' + id).html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
            var url = "<?= Url::toRoute(['load-list',
                'type' => Constants::_TYPE_CLIP,
                'filter' => Constants::_FILTER_MOST_VIEW,
                'typeLoad' => Constants::_AJAX_LOAD_HOME,
                'category' => \app\helpers\CConfig::GIAO_DUC,
                'order' => 0//order moi nhat
            ])?>";
            ajax(url, '#' + id);
        } else {
            $('#giaoduc-2').css('display', 'block');
            $('#giaoduc-1').css('display', 'none');
            $('#' + id).html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
            var url = "<?= Url::toRoute(['load-list',
                'type' => Constants::_TYPE_CLIP,
                'filter' => Constants::_FILTER_MOST_VIEW,
                'typeLoad' => Constants::_AJAX_LOAD_HOME,
                'category' => \app\helpers\CConfig::GIAO_DUC,
                'order' => 1//order xem nhieu
            ])?>";
            ajax(url, '#' + id);
        }

    }


    function displayThoitrang(id, tagA) {
        $("h4#thoitrang a").each(function () {
            $(this).removeClass('active');
        });
        tagA.addClass("active");
        //clips
        if (id == 'thoitrang-1') {
            $('#thoitrang-1').css('display', 'block');
            $('#thoitrang-2').css('display', 'none');
            $('#' + id).html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
            var url = "<?= Url::toRoute(['load-list',
                'type' => Constants::_TYPE_CLIP,
                'filter' => Constants::_FILTER_MOST_VIEW,
                'typeLoad' => Constants::_AJAX_LOAD_HOME,
                'category' => \app\helpers\CConfig::THOI_TRANG,
                'order' => 0//order moi nhat
            ])?>";
            ajax(url, '#' + id);
        } else {
            $('#thoitrang-2').css('display', 'block');
            $('#thoitrang-1').css('display', 'none');
            $('#' + id).html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
            var url = "<?= Url::toRoute(['load-list',
                'type' => Constants::_TYPE_CLIP,
                'filter' => Constants::_FILTER_MOST_VIEW,
                'typeLoad' => Constants::_AJAX_LOAD_HOME,
                'category' => \app\helpers\CConfig::THOI_TRANG,
                'order' => 1//order xem nhieu
            ])?>";
            ajax(url, '#' + id);
        }

    }

    function displayGame(id, tagA) {
        $("h4#game a").each(function () {
            $(this).removeClass('active');
        });
        tagA.addClass("active");
        //clips
        if (id == 'game-1') {
            $('#game-1').css('display', 'block');
            $('#game-2').css('display', 'none');
            $('#' + id).html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
            var url = "<?= Url::toRoute(['load-list',
                'type' => Constants::_TYPE_CLIP,
                'filter' => Constants::_FILTER_MOST_VIEW,
                'typeLoad' => Constants::_AJAX_LOAD_HOME,
                'category' => \app\helpers\CConfig::GAME,
                'order' => 0//order moi nhat
            ])?>";
            ajax(url, '#' + id);
        } else {
            $('#game-2').css('display', 'block');
            $('#game-1').css('display', 'none');
            $('#' + id).html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
            var url = "<?= Url::toRoute(['load-list',
                'type' => Constants::_TYPE_CLIP,
                'filter' => Constants::_FILTER_MOST_VIEW,
                'typeLoad' => Constants::_AJAX_LOAD_HOME,
                'category' => \app\helpers\CConfig::GAME,
                'order' => 1//order xem nhieu
            ])?>";
            ajax(url, '#' + id);
        }

    }


    function displayFilm(id, tagA) {
        $("h4#film a").each(function () {
            $(this).removeClass('active');
        });
        tagA.addClass("active");
        //clips
        if (id == 'film-1') {
            $('#film-1').css('display', 'block');
            $('#film-2').css('display', 'none');
            $('#' + id).html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
            var url = "<?= Url::toRoute(['load-list',
                'type' => Constants::_TYPE_FILM,
                'filter' => Constants::_FILTER_MOST_VIEW,
                'typeLoad' => Constants::_AJAX_LOAD_HOME,
                'order' => 0//order moi nhat
            ])?>";
            ajax(url, '#' + id);
        } else {
            $('#film-2').css('display', 'block');
            $('#film-1').css('display', 'none');
            $('#' + id).html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
            var url = "<?= Url::toRoute(['load-list',
                'type' => Constants::_TYPE_FILM,
                'filter' => Constants::_FILTER_MOST_VIEW,
                'typeLoad' => Constants::_AJAX_LOAD_HOME,
                'order' => 1//order xem nhieu
            ])?>";
            ajax(url, '#' + id);
        }
    }

    function ok() {
        location.reload();
    }
    function acceptPurchase(tag) {
        var type = parseInt($('#type').val());
        var packageId = parseInt($('#pk_id').val());
        if (1 == type) {//huy goi
            var url = '<?= Url::toRoute(["user/cancel-package"])?>';
        } else {//mua goi
            var url = '<?= Url::toRoute(["user/purchase-package"])?>';
        }

        $.ajax({
            url: url,
            type: "GET",
            data: {id: packageId},
            crossDomain: true,
            dataType: "text",
            success: function (result) {
                var data = JSON.parse(result);
                if (null != data && data['success']) {
                    $('#msg2').html(data['message']);
                } else {
                    $('#msg2').html(data['message']);
                }
                return;
            },
            error: function (result) {
                $('#msg2').html('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
            }
        });//end jQuery.ajax
        //shutdown model
        tag.attr('data-dismiss', 'modal');
        //show notice model
        $('#notice-a').click();

    }
    function muagoi() {
        var msisdn = '<?=UserHelper::getMsisdn()?>';
        if ('' == msisdn) {
            location.href = '/site/sso-login?return_url=/site/index';
            return;
        } else {
            window.location.assign("<?= \app\helpers\CConfig::URL_BASE ?>user/packages");
        }
    }
    function showPopup(isMyPackage, msg, packageId, packageName, price, tagA) {
        var msisdn = '<?=UserHelper::getMsisdn()?>';
        if ('' == msisdn) {
            location.href = '/site/sso-login?return_url=/site/index';
            return;
        }
        tagA.attr('data-target', '#buy-modal');
        if (1 == isMyPackage) {//huy goi
            $('#text-buy').html('HỦY GÓI');
            var msg = '' == msg ? 'Quý Khách đang sử dụng gói ' + packageName + '. Bạn có đồng ý hủy gói cước?' : '';
            $('#msg').html(msg);
        } else {//mua goi
            $('#text-buy').html('MUA GÓI');
            var msg = '' == msg ? 'Vui lòng mua gói ' + packageName + ' giá ' + price + 'Đ' : '';
            $('#msg').html(msg);
        }

        $('#pk_id').val(packageId);
        $('#type').val(isMyPackage);
        //tagA.onclick();
    }
</script>