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
        <?=ContentItems::widget(['type' => $type, 'content'=> $content, 'category' => isset($category) ? $category : null])?>
    <?php }
}
?>