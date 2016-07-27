<?php
namespace app\models;


use app\helpers\ApiHelper;
use app\helpers\UserHelper;
use yii\base\Model;
use Yii;

class RegisterForm extends Model {


    public $full_name;
    public $phone_number;
    public $password;
    public $confirm_password;
    public $email;
    public $address;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['phone_number'], 'required', 'message' => 'Số điện thoại không được để trống.'],
            //[['password'], 'required', 'message' => 'Password không được để trống'],
            //[['confirm_password'], 'required', 'message' => 'Xác nhận mật khẩu không được để trống'],
            //[['full_name'], 'required', 'message' => 'Họ và tên không được để trống'],
            ['email', 'email','message'=> 'Email không đúng định dạng'],
            [['full_name', 'address', 'phone_number'], 'string'],
          //  ['confirm_password', 'confirmPassword'],
            //['new_password', 'string', 'min' => '6', 'message' => 'Password should be 6 characters or more'],
//            [
//                ['confirm_password'],
//                'compare',
//                'compareAttribute' => 'password',
//                'message' => 'Xác nhận mật khẩu chưa đúng.',
//                'on' => 'create'
//            ],
//            [
//                ['confirm_password'],
//                'compare',
//                'compareAttribute' => 'new_password',
//                'message' => 'Xác nhận mật khẩu chưa đúng.',
//                'on' => 'change-password'
//            ],
           // [['new_password'], 'required', 'on' => 'change-password'],
          //  [['old_password', 'new_password', 'confirm_password'], 'required', 'on' => 'change-password'],
        ];
    }


    public function confirmPassword($attribute, $params) {
        if(!$this->hasErrors()) {
            if($this->password != $this->confirm_password || $this->confirm_password == null || $this->password == null) {
                $this->addError($attribute, 'Xác nhận mật khẩu không đúng!');
            }
        }
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'full_name' => 'Tên đầy đủ',
            'email' => 'Email',
            'password' => 'Mật khẩu',
            'confirm_password' => 'Nhập lại mật khẩu',
            'phone_number' => 'Số điện thoại',
            'address' => 'Địa chỉ',
        ];
    }

    /**
     * register
     *
     * @return array
     */
    public function register() {
        if($this->validate()) {
            return ApiHelper::register($this->phone_number);
        } else {
            return $this->getFirstErrors();
        }
    }

    public function getErrorsApi($errors) {
        $errorFields = array();
        if(is_array($errors)) {
            foreach($errors as $er) {
                if($this->hasProperty($er['field'])) {
                    $errorFields[$er['field']] = $er['message'];
                }
            }
        }
        return $errorFields;
    }

} 