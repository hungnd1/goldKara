<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/16/15
 * Time: 3:41 PM
 */
use app\helpers\Constants;
use app\widgets\ContentItems;
use yii\helpers\Url;

/* @var $category \app\models\Category */
?>
<div class="container padding-none">
    <!-- list contents-->
    <div class="row margin-none">

        <div class="list-video truyen-hinh">
            <h4 class="title" id="music">
                Danh Sách Nội Dung Yêu Thích
            </h4>

            <div class="clearfix"></div>
            <div class="dm-video" id="music-newest-1">
                <?php if(!empty($listContent)) {
                    /* @var $listContent \app\models\ListContents */
                    foreach($listContent->items as $content) {?>
                        <?=ContentItems::widget(['type' => Constants::_TYPE_SEARCH, 'content'=> $content, 'isFavorite' => true])?>
                    <?php }
                    ?>
                    <?php if($listContent->_meta['currentPage']< $listContent->_meta['pageCount']) {?>
                        <div id="show-more" style="text-align: center;width: 100%;margin-top: 10px;float: left; cursor: pointer;" onclick="showMore()">
                            <span style="color: #0481A3;font-weight: bold;border: 1px solid #999;background-color: #E7F2DC;width: 200px;border-radius: 4px;padding: 5px 38px;font-size: 13px;text-align: center;">Xem Thêm</span>
                        </div>
                    <?php }?>
                    <input type="hidden" name="page" id="page" value="<?= isset($listContent->_meta['currentPage']) ? $listContent->_meta['currentPage'] : 1?>">
                    <input type="hidden" name="pageCount" id="pageCount" value="<?= isset($listContent->_meta['pageCount']) ? $listContent->_meta['pageCount'] : 1?>">
                <?php } else {?>
                    <span style="color: red">Không có dữ liệu</span>
                <?php }?>
                <div id="last-content"></div>
            </div>
            <div class="dm-video" id="music-newest-2" style="display: none"></div>
        </div>


        <div class="clearfix"></div>
        <!-- end list content -->

    </div>
    <!-- /container -->
</div>
<script type="text/javascript">
    function showMore() {
        $('#last-content').html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
        var url = '<?= Url::toRoute(['user/favorites'])?>';
        var page = parseInt($('#page').val()) + 1;
        var pageCount = parseInt($('#pageCount').val());
        $.ajax({
            url: url,
            data: {
                'typeLoad': 1,
                'page': page
            },
            type: "Get",
            crossDomain: true,
            dataType: "text",
            success: function(result){
                if(null != result && '' != result) {
                    $(result).insertBefore('#last-content');
                    $('#page').val(page);
                    if(page == pageCount) {
                        $('#show-more').css('display', 'none');
                    }
                    $('#last-content').html('');
                } else {
                    $('#last-content').html('');
                }

                return;
            },
            error: function(result) {
                alert('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                $('#last-content').html('');
                return;
            }
        });//end jQuery.ajax
    }
    //add to favorite
    function removeFavorite(id) {
        var url = '<?= Url::toRoute(['user/add-favorite'])?>';
        $.ajax({
            url: url,
            data: {
                'contentId':id
            },
            type: "POST",
            crossDomain: true,
            dataType: "text",
            success: function(result){
                var rs = JSON.parse(result);
                if(rs['success']) {
                    alert('Qúy khách bỏ yêu thích nội dung thành công');
                    $("#like").prop("onclick", null);
                } else {
                    alert(rs['message']);
                }

                return;
            },
            error: function(result) {
                alert('Không thành công. Quý khách vui lòng thử lại sau ít phút.');
                return;
            }
        });//end jQuery.ajax
    }
</script>