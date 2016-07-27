<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/27/15
 * Time: 10:53 AM
 */
use app\helpers\Constants;
use app\widgets\ContentItems;

/* @var $listComments \app\models\ListComments */
/* @var $comment \app\models\Comment */
?>
<?php if(!empty($listComments)) {?>
    <?php if(1 == $type) {?> <div id="head-comment"></div> <?php }?>
    <?php foreach($listComments->items as $comment){?>
        <div class="media">
            <div class="media-left">
                <a href="#">
                    <img class="media-object avatar" src="<?=Yii::$app->request->baseUrl?>/advance/images/avatar-df.png" alt="...">
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-heading user-name"><?=$comment->msisdn?><span><?= date('d/m/Y H:i:s', $comment->create_date)?></span></h4>
                <p><?= $comment->content?></p>
            </div>
        </div>
<?php }?>
    <?php if(1 == $type){//load lai ?>
    <div id="last-comment"></div>
    <input type="hidden" name="page" id="page" value="<?= isset($listComments->_meta['currentPage']) ? $listComments->_meta['currentPage'] : 1?>">
    <input type="hidden" name="pageCount" id="pageCount" value="<?= isset($listComments->_meta['pageCount']) ? $listComments->_meta['pageCount'] : 1?>">
    <?php if((isset($listComments->_meta['currentPage']) && isset($listComments->_meta['pageCount']))
        && $listComments->_meta['currentPage'] < $listComments->_meta['pageCount']) {?>
        <div id="more" style="text-align: center;width: 100%;margin-top: 10px;float: left; cursor: pointer;" onclick="readMore()">
            <span style="color: #0481A3;font-weight: bold;border: 1px solid #999;background-color: #E7F2DC;width: 200px;border-radius: 4px;padding: 5px 38px;font-size: 13px;text-align: center;">Xem Thêm</span>
        </div>
    <?php }?>
        <?php }?>
<?php
}
?>