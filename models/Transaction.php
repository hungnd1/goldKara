<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/9/15
 * Time: 6:04 PM
 */

namespace app\models;


use yii\base\Model;

class Transaction extends Model {
    public $id;
    public $subscriber_id;
    public $msisdn;
    public $type;
    public $service_id;
    public $content_id;
    public $transaction_time;
    public $status;
    public $description;
    public $cost;
    public $channel;
    public $content_provider_id;
    public $service_name;
    public $content_name;

    public function __construct($values) {
        parent::setAttributes($values,false);
    }
} 