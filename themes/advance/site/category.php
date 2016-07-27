<?php
use app\helpers\Constants;
use app\widgets\ContentItems;
use yii\helpers\Url;

/* @var $cate \app\models\Category */
?>
<div class="container padding-none">
    <!-- list cate-->
    <div class="row margin-none">
        <?php if (isset($categories) && !empty($categories)) {
            $i = 0;
            foreach ($categories->items as $cate) {
                ?>
                <div class="list-video">
                    <h4 class="title" id="music"><?= $cate->display_name ?>
                        <a class="show-all"
                           href="<?= Url::toRoute(['site/list-content', 'cateId' => $cate->id, 'type' => $cate->type, 'cateName' => $cate->display_name]) ?>">
                            <img src="<?= Yii::$app->request->baseUrl ?>/advance/images/ic-show-all.png" alt=""></a>
                        <?php if ($cate->type != Constants::_TYPE_LIVE) { ?>
                            <span>
                    <a class="active"
                       onclick="displayOther('music-newest-1', <?= $cate->id ?>, <?= $cate->type ?> ,$(this));">Mới
                        nhất</a>  |
                    <a onclick="displayOther('music-newest-2', <?= $cate->id ?>, <?= $cate->type ?>, $(this));">Xem
                        nhiều</a>
                </span>
                        <?php } ?>
                    </h4>

                    <div class="clearfix"></div>
                    <div class="dm-video" id="music-newest-1">
                        <?php
                        if (!empty($cate->listContents)) {
                            /* @var $listMusic \app\models\ListContents */

                            foreach ($cate->listContents as $content) {
                                ?>
                                <?= ContentItems::widget(['type' => $cate->type, 'content' => $content, 'category' => $cate]) ?>
                                <?php
                            }
                            ?>
                        <?php } else { ?>

                        <?php } ?>

                    </div>
                    <div class="dm-video" id="music-newest-2" style="display: none">


                    </div>
                </div>

                <div class="clearfix"></div>
                <?php $i++;
            } ?>
        <?php } else { ?>
            <span style="color: red;  margin-left: 12px;">Không có dữ liệu</span>
        <?php } ?>
        <!-- end list cate -->

    </div>
</div>
<!-- /container -->
</div>

<script type="text/javascript">

    function displayOther(id, cateId, type, tagA) {
        $("h4#music a").each(function () {
            $(this).removeClass('active');
        });
        tagA.addClass("active");
        if (id == "music-newest-1") {
            $('#music-newest-1').css('display', 'block');
            $('#music-newest-2').css('display', 'none');
            $('#' + id).html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
            var url = "<?= Url::toRoute(['load-list',
                'filter' => Constants::_FILTER_MOST_VIEW,
                'typeLoad' => Constants::_AJAX_LOAD_HOME,
                'type' => isset($cate->type) && $cate->type != null ? $cate->type : 5,
                'order' => 0,
                'id' => isset($cate->type) && $cate->type != null && ($cate->type == 1 || $cate->type == 3) ? null : 5
            ])?>";
            url += '&category=' + cateId;
            ajax(url, '#' + id);
        } else {
            $('#music-newest-1').css('display', 'none');
            $('#music-newest-2').css('display', 'block');
            $('#' + id).html("<img style='margin-left: 33%;' src=<?=Yii::$app->request->baseUrl?>'/advance/images/loading.gif' />");
            var url = "<?= Url::toRoute(['load-list',
                'filter' => Constants::_FILTER_MOST_VIEW,
                'typeLoad' => Constants::_AJAX_LOAD_HOME,
                'type' => isset($cate->type) && $cate->type != null ? $cate->type : 5,
                'order' => 1,
                'id' => isset($cate->type) && $cate->type != null && ($cate->type == 1 || $cate->type == 3) ? null : 5
            ])?>";
            url += '&category=' + cateId;
            ajax(url, '#' + id);
        }


    }

</script>