<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/10/15
 * Time: 9:49 AM
 */

?>

<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/1/15
 * Time: 9:28 AM
 */
use app\helpers\Constants;
use app\widgets\ContentItems;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $live \app\models\Content */
/* @var $relatedContent \app\models\ListContents */
/* @var $listComments \app\models\ListComments */
/* @var $comment \app\models\Comment */
?>
<?php if(null != $live) {
    if($live->urls == '' ) $live->urls = 'http://10.84.82.56:1935/vod/mp4:sample.mp4/playlist.m3u8';
    ?>
    <div class="container padding-none">
        <div class="run-video" id="player">
            <div class="mark-play">
                <a class="bt-play" data-toggle="modal" data-target="#notice-view-modal" ><span class="glyphicon glyphicon-play-circle" aria-hidden="true"></span></a>
            </div>
            <div class="livePlay"><a href="javascript:void(0);" id="play"><i class="fa-play-circle-o"></i></a></div>

        </div>
        <div class="info-video">
            <div class="clearfix"></div>
            <h1><?=$live->display_name?></h1>

            <div class="clearfix"></div>

        </div>
        <!-- Modal mua video le confirm -->
        <div class="modal fade" id="notice-view-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="margin-top: 100px" >
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"><span style="color: #FD7D12" class="glyphicon glyphicon-bell" aria-hidden="true"></span> <b>THÔNG BÁO</b></h4>
                    </div>

                    <?php if($live->is_free == 1){ ?>
                        <div class="modal-body" id="msg-down">Bạn có muốn xem kênh này không?</div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Ðể Sau</button>
                            <button type="button" class="btn btn-primary" onclick="acceptBuyOdd($(this));">ÐỒNG Ý</button>
                            <a id="notice-a" data-toggle="modal" data-target="#notice-modal" data-dismiss="modal"></a>
                        </div>
                    <?php } else { ?>
                        <div class="modal-body" id="msg-down">Bạn phải mua gói kênh truyền hình trước khi xem.</div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Ðể Sau</button>
                            <button type="button" class="btn btn-primary" onclick="acceptBuyOdd($(this));">ÐỒNG Ý</button>
                            <a id="notice-a" data-toggle="modal" data-target="#notice-modal" data-dismiss="modal"></a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- end Modal mua video confirm -->
        <!-- Modal message notice-->

        <div class="modal fade" id="notice-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="margin-top: 100px" >
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"><span style="color: #FD7D12" class="glyphicon glyphicon-bell" aria-hidden="true"></span> <b>THÔNG BÁO</b></h4>
                    </div>
                    <div class="modal-body" id="msg2"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Ðể Sau</button>
                        <button type="button" class="btn btn-primary" onclick="ok();">ÐỒNG Ý</button>
                        <a id="notice-a" data-toggle="modal" data-target="#notice-modal" data-dismiss="modal"></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- end Modal message notice -->
    </div>
    <div class="container padding-none">
        <!-- noi dung lien quan start -->
        <div class="row margin-none">

            <div class="list-video">
                <h4 class="title" id="related">TRUYỀN HÌNH LIÊN QUAN</h4>
                <div class="clearfix"></div>
                <!-- danh sach hot nhat co lien quan toi noi dung start-->
                <div class="dm-video" id="music-newest-1">
                    <?php if(!empty($relatedContent)) {
                        foreach($relatedContent->items as $item) {?>
                            <?=ContentItems::widget(['type' => $item->type, 'content'=> $item])?>
                        <?php }
                        ?>
                    <?php } else {?>
                        <span style="color: red">Không có dữ liệu</span>
                    <?php }?>
                </div>
                <!-- danh sach hot nhat co lien quan toi noi dung end-->
                <!-- danh sach xem nhieu nhat co lien quan toi noi dung start-->
                <div class="dm-video" id="music-newest-2" style="display: none"></div>
                <!-- danh sach xem nhieu nhat co lien quan toi noi dung end-->
            </div>
            <div class="clearfix"></div>
            <input id="check_user" <?php if(\app\helpers\UserHelper::isGuest()){ ?> value="1" <?php }else{ ?> value ="0" <?php } ?>  type="hidden">
        </div>
        <!-- noi dung lien quan end -->
        <!-- binh luan noi dung start-->
        <div class="container padding-none">
            <div class="comment">
                <h4 class="title">BÌNH LUẬN</h4>
                <?php $form = ActiveForm::begin([
                    'id' => 'comment-form',
                ]); ?>
                <div class="form-group">
                    <textarea class="form-control" rows="5" id="comment"></textarea>
                </div>
                <button type="button" class="bt-send"  onclick="feedBack($(this));">Gửi bình luận</button>
                <?php ActiveForm::end();?>
                <div class="clearfix"></div>
                <div class="list-comment">
                    <div id="head-comment"></div>
                    <?php if(!empty($listComments)) {
                        foreach($listComments->items as $comment){?>
                            <div class="media">
                                <div class="media-left">
                                    <a href="#">
                                        <img class="media-object avatar" src="<?=Yii::$app->request->baseUrl?>/advance/images/avatar-df.png" alt="...">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading user-name"><?=$comment->msisdn?><<span><?php
                                            date_default_timezone_set('Asia/Bangkok');
                                            echo  date('d/m/Y H:i:s',$comment->create_date); ?>
                                        </span></h4>
                                    <p><?= $comment->content?></p>
                                </div>
                            </div>

                    <div id="last-comment"></div>
                    <input type="hidden" name="page" id="page" value="<?= isset($listComments->_meta['currentPage']) ? $listComments->_meta['currentPage'] : 1?>">
                    <input type="hidden" name="pageCount" id="pageCount" value="<?= isset($listComments->_meta['pageCount']) ? $listComments->_meta['pageCount'] : 1?>">
                    <?php if((isset($listComments->_meta['currentPage']) && isset($listComments->_meta['pageCount']))
                        && $listComments->_meta['currentPage'] < $listComments->_meta['pageCount']) {?>
                        <div style="text-align: center;width: 100%;margin-top: 10px;float: left; cursor: pointer;" onclick="readMore()">
                            <span style="color: #0481A3;font-weight: bold;border: 1px solid #999;background-color: #E7F2DC;width: 200px;border-radius: 4px;padding: 5px 38px;font-size: 13px;text-align: center;">Xem Thêm</span>
                        </div>
                    <?php }?>
                        <?php }?>
                    <?php } else { echo "<span style='text-align: center'>Chưa có bình luận.</span>";}?>
                </div>
            </div>
        </div>
        <!-- binh luan noi dung end-->
    </div>
    <!-- Modal confirm -->
    <div class="modal fade" id="notice-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top: 100px" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><span style="color: #FD7D12" class="glyphicon glyphicon-bell" aria-hidden="true"></span> <b>THÔNG BÁO</b></h4>
                </div>
                <div class="modal-body" id="msg2"><?= \app\helpers\UserHelper::isGuest() ? 'Qúy khách cần đăng nhập để sử dụng tính năng này' : '';?></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Để Sau</button>
                    <button type="button" class="btn btn-primary" onclick="login($(this))">ĐỒNG Ý</button>
                </div>
            </div>
        </div>
    </div>

    <!-- end Modal confirm -->

    <!-- player -->
    <script src="<?=Yii::$app->request->baseUrl?>/advance/js/jwplayer/jwplayer.js"></script>
    <script src="<?=Yii::$app->request->baseUrl?>/advance/js/ng_player.js"></script>
    <script src="<?=Yii::$app->request->baseUrl?>/advance/js/ng_swfobject.js"></script>
    <script src="<?=Yii::$app->request->baseUrl?>/advance/js/ParsedQueryString.js"></script>
    <!-- player end-->
    <script type="text/javascript">
        function login(tag) {
            location.href = '<?=Url::toRoute(['site/sso-login','return_url'=> '/site/video?id=' . $live->id . '&type=' . $live->type])?>';
        }

        //read more comment
        function readMore() {
            $('#last-comment').html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
            var url = '<?= Url::toRoute(['site/list-comments'])?>';
            var page = parseInt($('#page').val()) + 1;
            $.ajax({
                url: url,
                data: {
                    'contentId': <?=$live->id?>,
                    'type': 'comment',
                    'page': page
                },
                type: "Get",
                crossDomain: true,
                dataType: "text",
                success: function(result){
                    if(null != result && '' != result) {
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
                error: function(result) {
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
                if (null == text || '' == text) {
                    alert("Không thành công. Qúy khách vui lòng nhập lời bình.");
                    $('#comment').val('');
                    $('#comment').focus();
                    return;
                }
                $('#head-comment').html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
                var url = '<?= Url::toRoute(['site/feedback'])?>';
                $.ajax({
                    url: url,
                    data: {
                        'contentId': <?=$live->id?>,
                        'type': 'comment',
                        'content': text
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
                                    'contentId': <?=$live->id?>,
                                    'type': 1,//load lai comments
                                    'page': 1
                                },
                                type: "Get",
                                crossDomain: true,
                                dataType: "text",
                                success: function (result) {
                                    if (null != result && '' != result) {
                                        $('div .list-comment').html(result);
                                        if ($('#page').val() < $('#pageCount').val()) {
                                            $('#more').css('display', 'block');
                                        }
                                    } else {
                                        //$('#last-comment').html('');
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


        function acceptBuyOdd(tag) {

            var url = '<?= Url::toRoute(['user/buy-odd'])?>';
            var check = $('#check_user').val();
            var image = '<?=$live->img_poster?>';
            var url1 = '<?=$live->urls?>';
            var check1 = '<?= $live->is_free?1:0 ?>';
//            //url = 'http://10.84.82.56:1935/vod/mp4:sample.mp4/playlist.m3u8';
//            if (('' != url || null != url) && check == 1 || check == 0 && price == 0) {
//                loadPlayer(url, '', image);
//            }

            //url = 'http://10.84.82.56:1935/vod/mp4:sample.mp4/playlist.m3u8';
            if(check ==0 && (('' != url1 || null != url1) && check1 == 1 ) ){
                loadPlayer(url1, '', image);
                tag.attr('data-dismiss', 'modal');
            }else if (check == 1 ) {
                $('#msg2').html("Quý khách cần đăng nhâp để thực hiện chức năng này");
                $('#notice-a').click();
            } else {
                window.location.assign("http://vplus.vinaphone.com.vn/user/packages");
            }

        }
        //noi dung lien quan
        function displayRelated(id, tagA) {
            $( "h4#related a" ).each(function() {
                $(this).removeClass('active');
            });
            tagA.addClass("active");
            //music
            if(id == 'music-newest-1') {
                $('#music-newest-1').css('display', 'block');
                $('#music-newest-2').css('display', 'none');
            } else if(id == 'music-newest-2') {
                $('#music-newest-1').css('display', 'none');
                $('#music-newest-2').css('display', 'block');
                var data = $.trim($('#music-newest-2').html());
                if(null == data || '' == data) {
                    $('#music-newest-2').html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
                    var url = "<?= Url::toRoute(['load-list',
                    'type'=>$live->type,
                    'id'=>$live->id,
                    'filter'=>Constants::_FILTER_MOST_VIEW,
                    'typeLoad'=>Constants::_AJAX_LOAD_RELATED,
                ])?>";
                    ajax(url,'#music-newest-2');

                } else {
                }
            }
        }

    </script>
<?php } else {?>
    <span style="color: red">
    Nội dung không tồn tại
</span>
<?php }?>