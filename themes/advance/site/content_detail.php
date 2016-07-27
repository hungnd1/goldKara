<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/1/15
 * Time: 9:28 AM
 */
use app\helpers\Constants;
use app\helpers\UserHelper;
use app\widgets\ContentItems;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $content \app\models\Content */
/* @var $relatedContent \app\models\ListContents */
/* @var $listComments \app\models\ListComments */
/* @var $comment \app\models\Comment */
/* @var $listContent \app\models\ListContents */
?>

<?php if (null != $content) {
//   var_dump($content->list_category[0]['name']);exit;
    //$content->img_poster = Yii::$app->request->baseUrl . '/advance/images/a1.jpg';
    if ($content->urls == "") $content->urls = 'http://10.84.82.56:1935/vod/mp4:sample.mp4/playlist.m3u8';
    ?>
    <div class="container padding-none">
        <div class="run-video" id="player">
            <div class="price-view">Giá
                xem:<?php if ((!empty($content->price) && $content->price != null) && $content->is_free != 1) { ?> <?= $content->price ?> Đ <?php } else {
                    echo "Miễn Phí";
                } ?> </div>
            <div class="mark-play">
                <a class="bt-play" data-toggle="modal" data-target="#notice-view-modal"><span
                        class="glyphicon glyphicon-play-circle" aria-hidden="true"></span></a>
            </div>
            <img src="<?= $content->img_poster ?>" width="100%">

            <div class="livePlay"><a href="javascript:void(0);" id="play"><i class="fa-play-circle-o"></i></a></div>

        </div>
        <div class="info-video">
            <div class="clearfix"></div>
            <h1><?= $content->display_name ?>
                <span style="float: right">
                    <span class="star" style="margin-right: 20px">
                        <img
                            src="<?= Yii::$app->request->baseUrl ?>/advance/images/star/<?= $content->getRate() ?>_star.png"
                            height="19px">
                      </span>
                    <a class="num-view"><span class="glyphicon glyphicon-eye-open"
                                                                     aria-hidden="true">
                </span><?= $content->view_count ?></a>
                    <?php if ($content->is_favorite == 1) { ?>
                        <a id="like" href="javascript:void(0);" onclick="addFavorite();" class="bt-favorite"
                           style="color:#78BA1F "
                           data-toggle="tooltip"
                           data-placement="top">
                            <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
                            <span id="favorite_count"><?= $content->favorite_count ?></span></a>
                    <?php } else { ?>
                        <a id="like" href="javascript:void(0);" onclick="addFavorite();" class="bt-favorite"
                           data-toggle="tooltip"
                           data-placement="top">
                            <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
                            <span id="favorite_count"><?= $content->favorite_count ?></span></a>
                    <?php } ?>
            </span>
                <input id="check_error" value="" type="hidden">
                <input id="check" value="<?= $content->is_favorite ? 1 : 0 ?>" type="hidden">
                <input id="count" value="<?= $content->favorite_count ?>" type="hidden">
            </h1>
            <?php if ($content->is_series == 1) { ?>
                <div class="chap-dram clearfix">
                    <span>Tập</span>
                    <ul>
                        <?php if (!empty($listContent)) {
                            $i = 1;
                            foreach ($listContent->items as $item) {
                                ?>
                                <li id="test"><a onclick="filmDrama('<?php if (!empty($item->urls)) {
                                        echo $item->urls;
                                    } else {
                                        echo 'http://10.84.82.56:1935/vod/mp4:sample.mp4/playlist.m3u8';
                                    } ?>',$(this));"><?= $i++ ?></a></li>
                            <?php } ?>
                            <input
                                id="check_url" <?php if (!empty($listContent) && $content->urls != null) { ?> value="" <?php } else { ?> value="<?= $listContent->items[0]->urls ?>" <?php } ?>
                                type="hidden">
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <div class="clearfix"></div>
            <input
                id="check_user" <?php if (UserHelper::isGuest()) { ?> value="1" <?php } else { ?> value="0" <?php } ?>
                type="hidden">

            <p>
                <?php if (!UserHelper::isGuest()) { ?>
                    <span class="price-down"><?php echo "Giá tải: ";
                        if ($content->price_download != null){ ?><span><?= $content->price_download ?><?php echo "Đ";
                            } else {
                                echo "Miễn phí ";
                            } ?></span></span>
                    <a class="click-down" href="#file" class="navbar-right" data-toggle="modal"
                       data-target="#notice-download-modal">TẢI VỀ</a><br/>
                <?php } ?>
                <!-- Modal mua video le confirm -->

            <div class="modal fade" id="notice-view-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="margin-top: 100px">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel"><span style="color: #FD7D12"
                                                                            class="glyphicon glyphicon-bell"
                                                                            aria-hidden="true"></span> <b>THÔNG BÁO</b>
                            </h4>
                        </div>
                        <?php if ($content->is_free == 1) { ?>
                            <div class="modal-body" id="msg-down">Bạn có muốn xem video này không?</div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Để Sau</button>
                                <button type="button" class="btn btn-primary" onclick="acceptBuyOdd($(this));">ĐỒNG Ý
                                </button>
                                <a id="notice-a" data-toggle="modal" data-target="#notice-modal"
                                   data-dismiss="modal"></a>
                            </div>
                        <?php } else { ?>
                            <div class="modal-body" id="msg-down">Nội dung này có giá <?= $content->price_download ?> Đ.
                                Vui lòng xác nhận thanh toán?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Để Sau</button>
                                <button type="button" class="btn btn-primary" onclick="acceptBuyOdd($(this));">ĐỒNG Ý
                                </button>
                                <a id="notice-a" data-toggle="modal" data-target="#notice-modal"
                                   data-dismiss="modal"></a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- end Modal mua video confirm -->
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
                                                                            aria-hidden="true"></span> <b>THÔNG BÁO</b>
                            </h4>
                        </div>
                        <div class="modal-body" id="msg2"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Để Sau</button>
                            <button type="button" class="btn btn-primary" onclick="ok($(this));">ĐỒNG Ý</button>
                            <a id="notice-a" data-toggle="modal" data-target="#notice-modal" data-dismiss="modal"></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- end Modal message notice -->

            <!-- Modal message notice-->

            <div class="modal fade" id="notice-modal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="margin-top: 100px">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel"><span style="color: #FD7D12"
                                                                            class="glyphicon glyphicon-bell"
                                                                            aria-hidden="true"></span> <b>THÔNG BÁO</b>
                            </h4>
                        </div>
                        <div class="modal-body" id="msg3"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Để Sau</button>
                            <button type="button" class="btn btn-primary" onclick="ok1();">ĐỒNG Ý</button>
                            <a id="notice-a1" data-toggle="modal" data-target="#notice-modal1" data-dismiss="modal"></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- end Modal message notice -->
            <!-- Modal download confirm -->
            <div class="modal fade" id="notice-download-modal" tabindex="-1" role="dialog"
                 aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="margin-top: 100px">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel"><span style="color: #FD7D12"
                                                                            class="glyphicon glyphicon-bell"
                                                                            aria-hidden="true"></span> <b>THÔNG BÁO</b>
                            </h4>
                        </div>
                        <div class="modal-body"
                             id="msg-down"> <?php if (!UserHelper::isGuest() && $content->price_download != null) {
                                echo "Nội dung này có giá "; ?>  <?= $content->price_download ?>
                                <?php echo "Đ, vui lòng xác nhận thanh toán?";
                            } else if ($content->price_download == null) {
                                echo "Bạn có muốn tải miễn phí bộ film này không?";
                            } else {
                                echo "Quý khách cần đăng nhập để sử dụng tính năng này";
                            } ?></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Hủy</button>
                            <button type="button" class="btn btn-primary" onclick="acceptDownload($(this));"> ĐỒNG Ý
                            </button>
                            <a id="notice-a" data-toggle="modal" data-target="#notice-modal" data-dismiss="modal"></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end Modal download confirm -->
            </p>
            <div class="clearfix"></div>
            <p class="info-bot">
                <span>Thể loại:</span> <?= !empty($content->list_category[0]['name']) ? $content->list_category[0]['name'] : 'Đang cập nhật' ?>
                <br/>

                <span>Ngày phát hành:</span> <?= date('d/m/Y', $content->updated_at) ?><br/>

                <span>Đạo diễn:</span> <?= !empty($content->director) ? $content->director : 'Đang cập nhật' ?><br/>

                <span>Diễn viên:</span><?= !empty($content->actor) ? $content->actor : 'Đang cập nhật' ?><br/>
                <span>Luợt xem:</span><?= !empty($content->view_count) ? $content->view_count : 'Đang cập nhật' ?><br/>
                <span>Mô tả:</span><br/>
                <span id="short_description"
                      style="display: block;font-weight: normal"><?php if (!empty($content->short_description)) { ?><?= $content->short_description ?>...
                        <a onclick="javascript:$('#short_description').css('display','none');$('#full_description').css('display','block');">Xem
                            thêm &gt;&gt;</a><?php } ?></span>
            <span id="full_description" style="display: none;font-weight: normal"><?= $content->description ?>
                <a onclick="javascript:$('#short_description').css('display','block');$('#full_description').css('display','none');">
                    &lt;&lt;Thu gọn</a></span>
            </p>

        </div>
    </div>
    <div class="container padding-none">
        <!-- noi dung lien quan start -->
        <div class="row margin-none">

            <div class="list-video">
                <h4 class="title" id="related">LIÊN QUAN<span><a class="active"
                                                                 onclick="displayRelated('music-newest-1', $(this));">Mới
                            nhất</a>  |  <a onclick="displayRelated('music-newest-2', $(this));">Xem nhiều</a></span>
                </h4>

                <div class="clearfix"></div>
                <!-- danh sach hot nhat co lien quan toi noi dung start-->
                <div class="dm-video" id="music-newest-1">
                    <?php if (!empty($relatedContent)) {
                        foreach ($relatedContent->items as $item) { ?>
                            <?= ContentItems::widget(['type' => $content->type, 'content' => $item]) ?>
                        <?php }
                        ?>
                    <?php } else { ?>
                        <span style="color: red; margin-left: 10px">Không có dữ liệu</span>
                    <?php } ?>
                </div>
                <!-- danh sach hot nhat co lien quan toi noi dung end-->
                <!-- danh sach xem nhieu nhat co lien quan toi noi dung start-->
                <div class="dm-video" id="music-newest-2" style="display: none"></div>
                <!-- danh sach xem nhieu nhat co lien quan toi noi dung end-->
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- noi dung lien quan end -->
        <!-- binh luan noi dung start-->
        <div class="container padding-none">
            <div class="comment">
                <h4 class="title">BÌNH LUẬN</h4>
                <?php $form = ActiveForm::begin([
                    'id' => 'comment-form'
                ]); ?>
                <div class="form-group">
                    <input id="input-2" type="number"
                           value="0"  min=0 max=5 class="rating" data-min="0" data-max="5" step=1 data-size="xs">
                </div>
                <div class="form-group">
                    <textarea class="form-control" rows="5" id="comment"></textarea>
                </div>
                <button type="button" class="bt-send" onclick="feedBack($(this));">Gửi bình luận
                </button>
                <?php ActiveForm::end(); ?>
                <div class="clearfix"></div>
                <div class="list-comment">
                    <div id="head-comment"></div>
                    <?php if (!empty($listComments) && !empty($listComments->items)) {

                        foreach ($listComments->items as $comment) {
                            ?>
                            <div class="media">
                                <div class="media-left">
                                    <a href="#">
                                        <img class="media-object avatar"
                                             src="<?= Yii::$app->request->baseUrl ?>/advance/images/avatar-df.png"
                                             alt="...">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading user-name"><?= $comment->msisdn ?>
                                        <span><?php
                                            //date_default_timezone_set('Asia/Bangkok');
                                            echo date('d/m/Y H:i:s', $comment->create_date); ?>
                                        </span></h4>

                                    <p><?= $comment->content ?></p>
                                </div>
                            </div>
                        <?php } ?>
                        <div id="last-comment"></div>
                        <input type="hidden" name="page" id="page"
                               value="<?= isset($listComments->_meta['currentPage']) ? $listComments->_meta['currentPage'] : 1 ?>">
                        <input type="hidden" name="pageCount" id="pageCount"
                               value="<?= isset($listComments->_meta['pageCount']) ? $listComments->_meta['pageCount'] : 1 ?>">
                        <?php if ((isset($listComments->_meta['currentPage']) && isset($listComments->_meta['pageCount']))
                            && $listComments->_meta['currentPage'] < $listComments->_meta['pageCount']
                        ) { ?>
                            <div id="more"
                                 style="text-align: center;width: 100%;margin-top: 10px;float: left; cursor: pointer;"
                                 onclick="readMore()">
                                <span
                                    style="color: #0481A3;font-weight: bold;border: 1px solid #999;background-color: #E7F2DC;width: 200px;border-radius: 4px;padding: 5px 38px;font-size: 13px;text-align: center;">Xem Thêm</span>
                            </div>
                        <?php } ?>
                    <?php } else {
                        echo "<span style='text-align: center'>Chưa có bình luận.</span>";
                    } ?>

                </div>
            </div>
        </div>
        <!-- binh luan noi dung end-->
    </div>
    <!-- Modal confirm -->
    <!--<a id="requireLogin" class="navbar-right login-click" data-toggle="modal" data-target="#notice-modal"></a>-->
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
                <div class="modal-body"
                     id="msg2"><?= UserHelper::isGuest() ? 'Qúy khách cần đăng nhập để sử dụng tính năng này' : ''; ?></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Để Sau</button>
                    <button type="button" class="btn btn-primary" onclick="login($(this));">ĐỒNG Ý</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end Modal confirm -->
    <!-- player -->
    <script src="<?= Yii::$app->request->baseUrl ?>/advance/js/jwplayer/jwplayer.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/advance/js/ng_player.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/advance/js/ng_swfobject.js"></script>
    <script src="<?= Yii::$app->request->baseUrl ?>/advance/js/ParsedQueryString.js"></script>
    <!-- player end-->
    <script type="text/javascript">


        $(document).ready(function () {
            var viewcount = '<?= Url::toRoute(['user/view-count'])?>';
            var check = $('#check_user').val();
            var check_ = $('#check_url').val();
            var check1 = '<?= $content->is_free ? 1 : 0 ?>';
            var url1 = '<?=$content->urls?>';
            var image = '<?=$content->img_poster?>';
            var price = '<?= $content->price ? 1 : 0 ?>';
            var check_url = '<?= !empty($content->urls) && $content->urls != '' ? 0 : 1 ?>';
            if (check == 0 && (('' != url1 || null != url1) && check1 == 1 || check1 == 0 && price == 0)) {
                if (check_url == 0) {
                    loadPlayer(url1, '', image);
                    $.ajax({
                            url: viewcount,
                            data: {
                                'contentId': <?=$content->id?>,
                                'countView': <?= $content->view_count + 1 ?>
                            },
                            type: "POST",
                            crossDomain: true,
                            dataType: "text",
                        }
                    );
                } else {
                    loadPlayer(check_, '', image);
                    $.ajax({
                            url: viewcount,
                            data: {
                                'contentId': <?=$content->id?>,
                                'countView': <?= $content->view_count + 1 ?>
                            },
                            type: "POST",
                            crossDomain: true,
                            dataType: "text",
                        }
                    );
                }

            }


        });
        function ok(tag) {

            var check = $('#check_user').val();
            var check_url = $('#check_error').val();
//            alert(check_url);
            var url1 = '<?=$content->urls?>';
            var image = '<?=$content->img_poster?>';
            var viewcount = '<?= Url::toRoute(['user/view-count'])?>';
            if (check == 1 && check_url != 1) {
//                window.location.assign("https://vinaphone.com.vn/auth//login?service=http%3A%2F%2Fvplus.vinaphone.com.vn%2Fsite%2Fsso-login%3Freturn_url%3D%252Fsite%252Findex");
                location.href = '<?=Url::toRoute(['site/sso-login', 'return_url' => '/site/video?id=' . $content->id . '&cateId=' . $content->list_category[0]['id'] . '&cateName=' . $content->list_category[0]['name']])?>';
            }else if(check != 1 && check_url == 1){
//                alert(1);
                location.reload();
            } else {
                tag.attr('data-dismiss', 'modal');
                $('#notice-a').hide();
//                location.href = '<?=Url::toRoute(['site/sso-login', 'return_url' => '/site/video?id=' . $content->id . '&type=' . $content->type])?>';
                loadPlayer(url1, '', image);
                $.ajax({
                        url: viewcount,
                        data: {
                            'contentId': <?=$content->id?>,
                            'countView': <?= $content->view_count + 1 ?>
                        },
                        type: "POST",
                        crossDomain: true,
                        dataType: "text",
                    }
                );
            }
        }

        function ok1() {
            location.href = '<?=Url::toRoute(['site/sso-login', 'return_url' => '/site/video?id=' . $content->id . '&type=' . $content->type])?>';
        }
        //acceptBuyOdd
        function acceptBuyOdd(tag) {


            var url = '<?= Url::toRoute(['user/buy-odd'])?>';
            var viewcount = '<?= Url::toRoute(['user/view-count'])?>';
            var viewContent = '<?= Url::toRoute(['user/view-content']) ?>';
            var check = $('#check_user').val();
            var image = '<?=$content->img_poster?>';
            var url1 = '<?=$content->urls?>';
            var check1 = '<?= $content->is_free ? 1 : 0 ?>';
            var price = '<?= $content->price ? 1 : 0 ?>';
            if (check == 0 && (('' != url1 || null != url1) && check1 == 1 || check1 == 0 && price == 0)) {
                alert(1);
                    loadPlayer(url1, '', image);

                $.ajax({
                        url: viewContent,
                        data: {
                            'content_id': <?= $content->content_id?>,
                            'service_provider_id': <?= $content->service_provider_id ?>,
                            'content_provider_id': <?= $content->content_provider_id ?>,
                            'type': <?= $content->type ?>
                        },
                        type: "POST",
                        crossDomain: true,
                        dataType: "text",
                        success: function (result) {
                            return;
                        },

                        error: function (result) {
                            alert('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                        }
                    }
                );

                $.ajax({
                        url: viewcount,
                        data: {
                            'contentId': <?=$content->id?>,
                            'countView': <?= $content->view_count + 1 ?>
                        },
                        type: "POST",
                        crossDomain: true,
                        dataType: "text",
                        success: function (result) {
                            return;
                        },

                        error: function (result) {
                            alert('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                        }
                    }
                );

                tag.attr('data-dismiss', 'modal');
            } else if (check == 1) {
                $('#msg2').html("Quý khách cần đăng nhập để thực hiện chức năng này");
                $('#notice-a').click();

            } else {
                $.ajax({
                        url: url,
                        data: {
                            'contentId': <?=$content->id?>
                        },
                        type: "POST",
                        crossDomain: true,
                        dataType: "text",
                        success: function (result) {
                            var rs = JSON.parse(result);
                            if (rs['success']) {
                                $('#msg2').html(rs['message']);
                                $('#check_url').val('1');
                                $("input[id=check_error]").val(1);

                            } else {
                                $('#msg2').html(rs['message']);
                                $("input[id=check_error]").val(1);
                            }
                            return;
                        },

                        error: function (result) {
                            alert('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                        }
                    }
                )
                ;//end jQuery.ajax
                //shutdown model
                tag.attr('data-dismiss', 'modal');
                $('#notice-a').click();

                //show notice model
            }

        }
        //accept download
        function acceptDownload(tag) {

            var url = '<?= Url::toRoute(['user/download'])?>';
            var check = $('#check_user').val();
            var price = '<?= $content->price_download ? 1 : 0 ?>';
            if (check == 1 && price == 1) {
                window.location.assign("https://vinaphone.com.vn/auth//login?service=http%3A%2F%2Fvplus.vinaphone.com.vn%2Fsite%2Fsso-login%3Freturn_url%3D%252Fsite%252Findex");
            } else {
                $.ajax({
                    url: url,
                    data: {
                        'contentId': <?=$content->id?>
                    },
                    type: "POST",
                    crossDomain: true,
                    dataType: "text",
                    success: function (result) {
                        var rs = JSON.parse(result);
                        if (rs['success']) {
                            $('#notice-modal1').hide();
                            window.location = rs['url_download'];
                            //location.href = rs['url_download'];
                        } else {
                            $('#msg3').html(rs['message']);
                        }
                        return;
                    },
                    error: function (result) {
                        alert('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                    }
                });//end jQuery.ajax
                tag.attr('data-dismiss', 'modal');
                $('#notice-a1').click();
            }
        }
        //add to favorite
        function addFavorite() {
            //alert('1');
            var check = $('#check').val();
            var count = parseInt($('#count').val(), 10);
            var check_user = $('#check_user').val();
            if (check_user == 1) {
                $('#msg2').html("Quý khách cần đăng nhập để thực hiện chức năng này");
                $('#notice-a').click();
            } else if (check == 0 || check == null || check == '') {
                var url = '<?= Url::toRoute(['user/add-favorite'])?>';
                $.ajax({
                    url: url,
                    data: {
                        'contentId': <?=$content->id?>
                    },
                    type: "POST",
                    crossDomain: true,
                    dataType: "text",
                    success: function (result) {
                        var rs = JSON.parse(result);
                        if (rs['success']) {
                            //alert('Qúy khách đã thêm thành công nội dung vào danh sách yêu thích');
                            //$("#like").prop("onclick", null);
                            $("#like").css("color", '#78BA1F');
                            $('#favorite_count').html(count + 1);
                            document.getElementById('check').value = 1;
                            document.getElementById('count').value = count + 1;
                            alert("Quý khách đã thêm thành công nội dung vào danh sách yêu thích. Nội dung hiển thị trong phần quản lý danh sách yêu thích")
                        } else {
                            alert(rs['message']);
                        }
                        return;
                    },
                    error: function (result) {
                        alert('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                        return;
                    }
                });
            }//end jQuery.ajax
            else {
                var url = '<?= Url::toRoute(['user/un-favorite'])?>';
                $.ajax({
                    url: url,
                    data: {
                        'contentId':<?=$content->id?>
                    },
                    type: "POST",
                    crossDomain: true,
                    dataType: "text",
                    success: function (result) {
                        var rs = JSON.parse(result);
                        if (rs['success']) {
                            //alert('Qúy khách bỏ yêu thích nội dung thành công');
                            //$("#like").prop("onclick", null);
                            $("#like").css("color", '#999');
                            $('#favorite_count').html(count - 1);
                            document.getElementById('check').value = 0;
                            document.getElementById('count').value = count - 1;
                        } else {
                            alert(rs['message']);
                        }

                        return;
                    },
                    error: function (result) {
                        alert('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                        return;
                    }
                });//end jQuery.ajax

            }
        }
        //remove favorite
        function removeFavorite() {
            var url = '<?= Url::toRoute(['user/un-favorite'])?>';
            $.ajax({
                url: url,
                data: {
                    'contentId':<?=$content->id?>
                },
                type: "POST",
                crossDomain: true,
                dataType: "text",
                success: function (result) {
                    var rs = JSON.parse(result);
                    if (rs['success']) {
                        alert('Qúy khách bỏ yêu thích nội dung thành công');
                        $("#like").prop("onclick", null);
                        $("#like").css("color", '#78BA1F');
                        $('#favorite_count').html(<?=$content->favorite_count?> -1);
                        window.location.reload();
                    } else {
                        alert(rs['message']);
                    }

                    return;
                },
                error: function (result) {
                    alert('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                    return;
                }
            });//end jQuery.ajax
        }

        //require login
        function login(tag) {
            location.href = '<?=Url::toRoute(['site/sso-login', 'return_url' => '/site/video?id=' . $content->id])?>';
        }


        //readmore comment
        function readMore() {
            $('#last-comment').html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
            var url = '<?= Url::toRoute(['site/list-comments'])?>';
            var page = parseInt($('#page').val()) + 1;
            $.ajax({
                url: url,
                data: {
                    'contentId': <?=$content->id?>,
                    'type': 'comment',
                    'page': page
                },
                type: "Get",
                crossDomain: true,
                dataType: "text",
                success: function (result) {
                    if (null != result && '' != result) {
                        $(result).insertBefore('#last-comment');
                        $('#page').val(page);
                        if ($('#page').val() == $('#pageCount').val()) {
                            $('#more').css('display', 'none');
                        }
                        $('#last-comment').html('');
                    } else {
                        $('#last-comment').html('');
                    }

                    return;
                },
                error: function (result) {
                    alert('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                    $('#last-comment').html('');
                    return;
                }
            });//end jQuery.ajax
        }

        //comment
        function feedBack(tag) {
            var check_user = $('#check_user').val();
            if (check_user == 1) {
                $('#msg2').html("Quý khách cần đăng nhập để thực hiện chức năng này");
                $('#notice-a').click();
            } else {
                var text = 'undefined' != $.trim($('#comment').val()) ? $.trim($('#comment').val()) : '';
                var rating = $('#input-2').val();
//                alert(rating);
                if (null == text || '' == text) {
                    alert("Không thành công. Qúy khách vui lòng nhập lời bình.");
                    $('#comment').val('');
                    $('#comment').focus();
                    return;
                }
                if(null == rating || '' == rating || 0 == rating){
                    alert("Không thành công. Qúy khách vui lòng đánh giá.");
                    $('#rating').val('');
                    $('#rating').focus();
                    return;
                }
                $('#head-comment').html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
                var url = '<?= Url::toRoute(['site/feedback'])?>';
                $.ajax({
                    url: url,
                    data: {
                        'contentId': <?=$content->id?>,
                        'type': 'comment',
                        'content': text,
                        'rating': rating
                    },
                    type: "POST",
                    crossDomain: true,
                    dataType: "text",
                    success: function (result) {
                        var rs = JSON.parse(result);
                        if (rs['success']) {
                            // var data = result['data'];
                            //$(html).insertAfter('#head-comment');
                            $('#head-comment').html('');
                            //alert(rs['message']);
                            $('#comment').val('');
                            var url = '<?= Url::toRoute(['site/list-comments'])?>';
                            $('#page').val(1);
                            $.ajax({
                                url: url,
                                data: {
                                    'contentId': <?=$content->id?>,
                                    'type': 1,//load lai comments
                                    'page': 1
                                },
                                type: "GET",
                                crossDomain: true,
                                dataType: "text",
                                success: function (result) {
                                    if (null != result && '' != result) {
                                        $('div .list-comment').html(result);
                                        if ($('#page').val() < $('#pageCount').val()) {
                                            $('#more').css('display', 'block');
                                        }
                                    } else {
                                        $('#last-comment').html('');
                                    }

                                    return;
                                },
                                error: function (result) {
                                    alert('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                                    return;
                                }
                            });//end jQuery.ajax
                            return;
                        } else {
                            alert(rs['message']);
                            $('#head-comment').html('');
                        }

                    },
                    error: function (result) {
                        alert('Không thành công. Qúy khách vui lòng thử lại sau ít phút.');
                        $('#head-comment').html('');
                        return;
                    }
                });//end jQuery.ajax
            }
        }

        //display noi dung lien quan
        function displayRelated(id, tagA) {
            $("h4#related a").each(function () {
                $(this).removeClass('active');
            });
            tagA.addClass("active");
            //music
            if (id == 'music-newest-1') {
                $('#music-newest-1').css('display', 'block');
                $('#music-newest-2').css('display', 'none');
                $('#music-newest-1').html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
                var url = "<?= Url::toRoute(['load-list',
                    'type' => $content->type,
                    'id' => $content->id,
                    'order' => 0,
                    'category' => $content->type,
                    'filter' => Constants::_FILTER_MOST_VIEW,
                    'typeLoad' => Constants::_AJAX_LOAD_RELATED,
                ])?>";
                ajax(url, '#music-newest-1');
            } else if (id == 'music-newest-2') {
                $('#music-newest-1').css('display', 'none');
                $('#music-newest-2').css('display', 'block');
                var data = $.trim($('#music-newest-2').html());
                $('#music-newest-2').html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
                var url = "<?= Url::toRoute(['load-list',
                    'type' => $content->type,
                    'id' => $content->id,
                    'order' => 1,
                    'category' => $content->type,
                    'filter' => Constants::_FILTER_MOST_VIEW,
                    'typeLoad' => Constants::_AJAX_LOAD_RELATED,
                ])?>";
                ajax(url, '#music-newest-2');
            }
        }
        function filmDrama($url, tagA) {
            var check1 = '<?= $content->is_free ? 1 : 0 ?>';
            var price = '<?= $content->price ? 1 : 0 ?>';
            if (check1 == 1 || check1 == 0 && price == 0) {
                loadPlayer($url, '', '');
                $("li#test a").each(function () {
                    $(this).removeClass('active');
                });
                tagA.addClass("active");
            } else if (check == 1) {
                $('#msg2').html("Quý khách cần mua bộ phim này trước khi xem!");
                $('#notice-a').click();
            }
        }
    </script>
<?php } else { ?>
    <span style="color: red">
    Nội dung không tồn tại
</span>
<?php } ?>