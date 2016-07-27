<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/27/15
 * Time: 10:55 AM
 */
use yii\helpers\Url;
/* @var $category \app\models\Category */
/* @var $live \app\models\Content */
$strCate = isset($category) && !empty($category) ? '&cateId=' . $category->id . '&cateName=' . $category->display_name : '';
?>
<?php if(null != $live){?>
    <div class="video-in-list">
      <span class="set-video">
          <a href="<?=Url::toRoute(['live','id'=> $live->id,'type'=>$live->type]) . $strCate?>">
              <img class="thumb-video" src="<?= str_replace("http://api.msp.vn/","http://103.31.126.166/",$live->img_thumbnail)?>">
          </a>
      </span>
    </div>
<?php }?>