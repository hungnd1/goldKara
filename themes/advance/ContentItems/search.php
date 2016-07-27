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
          <a href="<?=Url::toRoute(['site/live','id'=> $content->id,'type'=>Constants::_TYPE_SEARCH])?>">
              <img class="thumb-video" src="<?= $content->image?>">
          </a>
      </span>
    </div>
    <?php } else {?>

    <div class="video-in-list">
        <span class="set-video">
             <a href="<?=Url::toRoute(['site/video','id'=> $content->id,'type'=>Constants::_TYPE_SEARCH])?>"><img class="thumb-video" src="<?= $content->image?>"></a>
        </span>
        <h4 class="media-heading name-content"> <?= $content->display_name?> </h4>
        <div class="clearfix"></div>
        <p class="info-view"><img src="<?=Yii::$app->request->baseUrl?>/advance/images/ic-view.png" height="12"><span class="num-view"><?= $content->rating_count?></span><span
                class="price-view">
            </span>
        </p>
    </div>
    <?php }?>
<?php } ?>