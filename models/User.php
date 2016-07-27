<?php

namespace app\models;

use Yii;
use yii\base\Model;


class User extends Model
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $id;
    public $msisdn;
    public $username;
    public $full_name;
    public $password;
    public $confirm_password;
    public $new_password;
    public $old_password;
    public $birthday;
    public $sex;
    public $email;
    public $role;
    public $client_type;
    public $status;
    public $created_at;
    public $updated_at;
    public $type;
    public $avatar_url;
    public $content_provider_id;
    public $service_provider_id;
    public $service_provider_name;
    public $using_promotion;
    public $skype_id;
    public $google_id;
    public $facebook_id;
    public $auto_renew;
    public $day;
    public $month;
    public $year;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['msisdn'], 'required'],
            [['msisdn', 'username','full_name','password', 'email','avatar_url'], 'string', 'max' => 255],
            [['status','role','updated_at','birthday','created_at','sex', 'day','month','year'], 'integer'],
            ['password', 'string', 'min' => '6', 'message' => 'Password should be 6 characters or more'],
            ['confirm_password', 'string', 'min' => '6', 'message' => 'Password should be 6 characters or more'],
            ['new_password', 'string', 'min' => '6', 'message' => 'Password should be 6 characters or more'],
            [['old_password', 'new_password', 'confirm_password'],'required','on'=>'change-password'],
            [
                ['confirm_password'],
                'compare',
                'compareAttribute' => 'new_password',
                'message' => 'Xác nhận mật khẩu chưa đúng.',
                'on' => 'change-password'
            ],
        ];
    }

    public function __construct($data) {
        $this->setAttributes($data);
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            //'id' => Yii::t('app', 'ID'),
            'msisdn' => Yii::t('app', 'Phone number'),
            'password' => Yii::t('app', 'Password'),
            'confirm_password' => Yii::t('app', 'Confirmed Password'),
            'email' => Yii::t('app', 'Email'),
            //'status' => Yii::t('app', 'Status'),
            //'created_at' => Yii::t('app', 'Created At'),
            //'updated_at' => Yii::t('app', 'Updated At'),
            //'type' => Yii::t('app', 'Type'),
            //'service_provider_id' => Yii::t('app', 'Service Provider ID'),
            //'content_provider_id' => Yii::t('app', 'Content Provider ID'),
            //'parent_id' => Yii::t('app', 'Parent ID'),
        ];
    }
}
