<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/27/15
 * Time: 10:54 AM
 */
use app\helpers\Constants;
use app\models\Content;
use yii\helpers\Url;

/* @var $content \app\models\Content */
/* @var $category \app\models\Category */
?>
<?php if (null != $content) { ?>
    <?php if(Constants::_TYPE_LIVE == $content->type) {?>
    <div class="video-in-list">
      <span class="set-video">
          <a href="<?=Url::toRoute(['site/live','id'=> $content->content_id,'type'=>Constants::_TYPE_SEARCH])?>">
              <img class="thumb-video" src="<?= str_replace(\app\helpers\CConfig::URL_API,\app\helpers\CConfig::URL_REPLACE,$content->img_thumbnail)?>">
          </a>
      </span>
    </div>
    <?php } else {?>

    <div class="video-in-list">
        <span class="set-video">
             <a href="<?=Url::toRoute(['site/video','id'=> $content->content_id,'type'=>Constants::_TYPE_SEARCH])?>"><img class="thumb-video" src="<?= str_replace("http://api.msp.vn/","http://103.31.126.166/",$content->img_thumbnail)?>"></a>
        </span>
        <h4 class="media-heading name-content"> <?= str_replace(substr($content->display_name,55,strlen($content->display_name)),'...',$content->display_name)?> </h4>
        <div class="clearfix"></div>
        <p class="info-view"><img src="<?=Yii::$app->request->baseUrl?>/advance/images/ic-view.png" height="12"><span class="num-view"><?= $content->view_count?></span><span
                class="price-view">
                <?php if(!empty($content->price) && $content->is_free != 1) {?>Giá: <span><?= $content->price?>Đ</span><?php } else {?>
                     <span>Xem miễn phí</span>
        <?php }?>
            </span>
        </p>
    </div>
    <?php }?>
<?php } ?>