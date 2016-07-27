<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/27/15
 * Time: 11:04 AM
 */

namespace app\widgets;


use app\helpers\Constants;
use yii\base\Widget;

class ContentItems extends Widget {
    public $type = 6;
    public $content = null;
    public $isFavorite = false;
    public $category = false;

    public function run() {

        if(Constants::_TYPE_MUSIC == $this->type || Constants::_TYPE_CLIP == $this->type || Constants::_TYPE_FILM == $this->type || Constants::_TYPE_NEWS == $this->type  || Constants::TYPE_NHIPSONGTRE == $this->type || Constants::TYPE_GAME == $this->type || Constants::TYPE_GIAODUC == $this->type || Constants::TYPE_FASHION == $this->type || Constants::TYPE_SAO == $this->type) {
            return $this->render('//ContentItems/video',['video' => $this->content,'isFavorite'=> $this->isFavorite,'category'=>$this->category]);
        } else if(Constants::_TYPE_LIVE == $this->type) {
            return $this->render('//ContentItems/live',['live' => $this->content, 'category'=>$this->category]);
        } else if(Constants::_TYPE_SEARCH == $this->type) {
            return $this->render('//ContentItems/search',['content' => $this->content,'category'=>$this->category,'isFavorite'=>$this->isFavorite]);
        }
    }
} 