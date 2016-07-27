<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/26/15
 * Time: 9:51 AM
 */

namespace app\models;


use app\helpers\Constants;
use yii\base\Model;

class Content extends Model {
    const _IS_FREE = 10;

    public $id;
    public $display_name;
    public $type;
    public $tags;
    public $short_description;
    public $description;
    public $content;
    public $duration;
    public $urls;
    public $version_code;
    public $version;
    public $view_count;
    public $is_series;
    public $download_count;
    public $like_count;
    public $dislike_count;
    public $rating;
    public $rating_count;
    public $comment_count;
    public $favorite_count;
    public $is_free;
    public $price;
    public $price_download;
    public $price_gift;
    public $images;
    public $status;
    public $created_at;
    public $updated_at;
    public $honor;
    public $content_provider_id;
    public $approved_at;
    public $service_provider_id;
    public $admin_note;
    public $img_thumbnail;
    public $img_poster;
    public $img_slide;
    public $is_favorite;
    public $author;
    public $director;
    public $actor;
    public $country;
    public $list_category;
    public $content_id;
    public $content_name;

    public function __construct($data){
        parent::setAttributes($data, false);
        if(empty($this->display_name)){
            $this->display_name = $this->content_name;
        }
        if(empty($this->content_id)){
            $this->content_id = $this->id;
        }
        if(!empty($this->images) && is_array($this->images)) {
            foreach($this->images as $img) {
                if(Constants::_IMAGE_TYPE_POSTER == $img['type']) {
                    $this->img_poster = $img['link'];
                } else if(Constants::_IMAGE_TYPE_THUMBNAIL == $img['type']){
                    $this->img_thumbnail = $img['link'];
                } else if(Constants::_IMAGE_TYPE_SLIDER == $img['type']){
                    $this->img_slide = $img['link'];
                }
            }
        } else if(!empty($this->images) && !is_array($this->images)) {
            $this->img_thumbnail = $this->images;
        }

        //list_category



    }
    public function getRate() {

        if (4.5 < $this->rating && $this->rating <= 5) {
            $number_start = '5';
        } else if (4 < $this->rating && $this->rating <= 4.5) {
            $number_start = '4.5';
        } else if (3.5 < $this->rating && $this->rating <= 4) {
            $number_start = '4';
        } else if (3 < $this->rating && $this->rating <= 3.5) {
            $number_start = '3.5';
        } else if (2.5 < $this->rating && $this->rating <= 3) {
            $number_start = '3';
        } else if (2 < $this->rating && $this->rating <= 2.5) {
            $number_start = '2.5';
        } else if (1.5 < $this->rating && $this->rating <= 2) {
            $number_start = '2';
        } else if (1 < $this->rating && $this->rating <= 1.5) {
            $number_start = '1.5';
        } else if (0.5 < $this->rating && $this->rating <= 1) {
            $number_start = '1';
        } else if (0 < $this->rating && $this->rating <= 0.5) {
            $number_start = '0.5';
        } else {
            $number_start = '0';
        }

        return $number_start;
    }
} 