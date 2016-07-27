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

        <div class="list-video">
            <h4 class="title" id="music">
                <?= $category->display_name?>
            </h4>

            <div class="clearfix"></div>
            <div class="dm-video" id="music-newest-1">
                <?php if(!empty($listContent)) {
                    /* @var $listContent \app\models\ListContents */
                    foreach($listContent->items as $content) {?>
                        <?=ContentItems::widget(['type' => $category->type,'category' => $category, 'content'=> $content])?>
                    <?php }
                    ?>
                    <?php $pagination = new \yii\data\Pagination(['totalCount'=> $listContent->_meta['totalCount'],'pageSize'=>$listContent->_meta['perPage']]) ?>
                    <?php if(isset($pagination) && !empty($pagination)) {?>
                        <div>
                            <?= \yii\widgets\LinkPager::widget([
                                'pagination' => $pagination,
                            ]);
                            ?>
                        </div>
                    <?php }?>
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
    //readmore comment
    function showMore() {
        $('#last-content').html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
        var url = '<?= Url::toRoute(['site/load-list'])?>';
        var page = parseInt($('#page').val()) + 1;
        var pageCount = parseInt($('#pageCount').val());
        $.ajax({
            url: url,
            data: {
                'category': <?=$category->id?>,
                'type': <?=$category->type?>,
                'typeLoad': <?=Constants::_AJAX_LOAD_LIST?>,
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
</script>