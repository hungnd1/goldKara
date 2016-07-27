<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/27/15
 * Time: 10:54 AM
 */
use app\models\Content;
use yii\helpers\Url;

/* @var $video \app\models\Content */
/* @var $category \app\models\Category */
?>
<?php if (null != $video) {
//    var_dump($video);exit;
    $strCate = isset($category) && !empty($category) ? '&cateId=' . $category->id . '&cateName=' . $category->display_name : '';
    ?>
    <div class="video-in-list">
        <span class="set-video">
             <a href="<?=Url::toRoute(['video','id'=> $video->id,'type'=>$video->type,]) . $strCate?>">
                 <img class="thumb-video" src="<?= str_replace(\app\helpers\CConfig::URL_API,\app\helpers\CConfig::URL_REPLACE,$video->img_thumbnail)?>"></a>
        </span>
        <h4 class="media-heading name-content"> <?= str_replace(substr($video->display_name,55,strlen($video->display_name)),'...',$video->display_name)?> </h4>
        <div class="clearfix"></div>
        <p class="info-view"><img src="<?=Yii::$app->request->baseUrl?>/advance/images/ic-view.png" height="12"><span class="num-view"><?= $video->view_count?></span><span
                class="price-view">
                <?php if($video->is_free != 1 && !empty($video->price) && $video->price !=null ) {?>Giá: <span><?= $video->price?>Đ</span><?php } else {?>
                     <span>Xem miễn phí</span>
        <?php }?>
            </span>

        </p>
    </div>
<?php } ?>
<script type="text/javascript">
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
