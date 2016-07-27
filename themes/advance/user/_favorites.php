<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/27/15
 * Time: 10:53 AM
 */
use app\helpers\Constants;
use app\widgets\ContentItems;

/* @var $listContent \app\models\ListContents */
/* @var $content \app\models\Content */
?>
<?php if(!empty($listContent)) {
    /* @var $listContent \app\models\ListContents */
    foreach($listContent->items as $content) {?>
        <?=ContentItems::widget(['type' => 6, 'content'=> $content, 'isFavorite' => true])?>
    <?php }
}
?>
<?php if($video->is_favorite) {?>
<a id="like" href="javascript:void(0);" onclick="removeFavorite(<?=$video->id?>);" class="bt-favorite"  data-toggle="tooltip" data-placement="top" title="B? thích video này">
    <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
    <?php }?>
