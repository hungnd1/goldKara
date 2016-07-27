<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/28/15
 * Time: 3:31 PM
 */

namespace app\models;


use app\widgets\ContentItems;
use yii\base\Model;

class Category extends Model
{
    public $id;
    public $display_name;
    public $service_provider_id;
    public $ascii_name;
    public $type;
    public $description;
    public $status;
    public $order_number;
    public $parent_id;
    public $path;
    public $level;
    public $child_count;
    public $images;
    public $created_at;
    public $updated_at;
    public $admin_note;
    public $img_thumbnail;
    public $img_poster;
    public $img_slide;
    public $children;
    public $contents;
    public $listContents;

    public function __construct($data){
        parent::setAttributes($data, false);
        if(!empty($this->images) && is_array($data)) {
            $this->img_poster = $this->images[0];
            $this->img_thumbnail = $this->images[1];
            $this->img_slide = $this->images[2];
        }
        if(!empty($this->contents) && is_array($this->contents)) {
            $this->listContents = [];
            foreach($this->contents as $it) {
                $content = new Content($it);
                array_push($this->listContents, $content);
            }
        }
    }

} 