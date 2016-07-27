<?php
/**
 * Created by PhpStorm.
 * User: Tien
 * Date: 10/30/14
 * Time: 4:39 PM
 */

namespace app\widgets;


use yii\base\Widget;

class ShowMore extends Widget{
    public $type_view = 1;
    public $params = array();
    public $user_id = 0;
    public $keyword = '';
    public $pageCount = 1;
    public $page = 2;
    public $html = '';
    public $url_action = '';
    public $content_id = 0;
    public $pageName = '';
    public $pageCountName = '';
    public $pager_id = '';

    public function init() {
        $this->params['url_action'] = $this->url_action;
        $this->params['html'] = $this->html;
        $this->params['user_id'] = $this->user_id;
        if(!empty($this->keyword)) {
            $this->params['keyword'] = $this->keyword;
        }
        if($this->content_id > 0) {
            $this->params['id'] = $this->content_id;
        }

        $this->params['page'] = $this->page;
        $this->params['pageCount'] = $this->pageCount;
        $this->params['pageName'] = !empty($this->pageName) ? $this->pageName: 'page';
        $this->params['pageCountName'] = !empty($this->pageCountName) ? $this->pageCountName : 'pageCount';
        $this->params['pager_id'] = !empty($this->pager_id) ? $this->pager_id : 'pager';
    }

    public function run() {
        if($this->type_view == 1) {
            return $this->render("//ShowMore/index", $this->params);
        }
    }
}