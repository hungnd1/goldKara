<?php

namespace app\models;

use app\helpers\ApiHelper;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $phone_number;
    public $password;
    public $rememberMe = true;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['phone_number', 'password'], 'required'],
            // rememberMe must be a boolean value
           // ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
          //  ['password', 'validatePassword'],
        ];
    }
    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        return ApiHelper::apiQuery([ApiHelper::API_LOGIN,'username' => $this->phone_number,'password'=> $this->password], null, false);
    }

}
