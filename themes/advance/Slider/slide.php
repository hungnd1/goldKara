<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/26/15
 * Time: 9:25 AM
 */
use app\widgets\ContentItems;
use yii\helpers\Url;

?>
<div class="container-fluid padding-none">
    <div class="slide-banner">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <?php if(null != $listSlideContent) {
                    $i=0;
                foreach($listSlideContent->items as $item) { ?>
                <li data-target="#carousel-example-generic" data-slide-to="<?= $i ?>" <?php if($i==0){ ?> class="active" <?php } ?>></li>
                <?php $i++;}}?>
            </ol>

            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
            <?php if(null != $listSlideContent) {
                /* @var $listSlideContent \app\models\ListContents */
                /* @var $item \app\models\Content */
                $i=0;
                foreach($listSlideContent->items as $item) { $i++;?>
                    <div class="item <?php if($i== 1)echo "active"; ?>">
                        <a href="<?=Url::toRoute(['view','id'=> $item->id])?>">
                            <img src="<?=$item->image?>" alt="..."></a>
                    </div>
            <?php } } else {?>
                <div class="item active">
                    <a href="<?=Url::toRoute(['video','id'=> 495,'type'=>1])?>">
                        <img src="<?=Yii::$app->request->baseUrl?>/advance/images/slide-1.jpg" alt="...">
                    </a>
                </div>
                <div class="item">
                    <a href="<?=Url::toRoute(['video','id'=> 496,'type'=>1])?>">
                        <img src="<?=Yii::$app->request->baseUrl?>/advance/images/slide-2.jpg" alt="...">
                    </a>
                </div>
                <div class="item">
                    <a href="<?=Url::toRoute(['video','id'=> 497,'type'=>1])?>">
                        <img src="<?=Yii::$app->request->baseUrl?>/advance/images/slide-3.jpg" alt="...">
                    </a>
                </div>
            <?php }?>
            </div>
            <!-- Controls -->
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

</div>