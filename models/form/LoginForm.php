<?php

namespace app\models\form;

use app\models\Users;
use yii\base\Model;
use Yii\app\security;

class LoginForm extends Model
{
    public $email;
    public $password;

    private $_user;

    public function attributeLabels()
    {
        return
        [
        'email' => 'EMAIL ',
        'password' => 'ПАРОЛЬ ',
        ];
    }

    public function rules()
    {
        return
        [
            [['email', 'password'], 'required'],

            [['password'], 'validatePassword']
        ];
    }
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !\Yii::$app->security->validatePassword($this->password, $user->password)) {
                $this->addError($attribute, 'Неправильный email или пароль');
            }
        }
    }

      public function getUser()
      {
          if ($this->_user === null) {
              $this->_user = Users::findOne(['email' => $this->email]);
          }

          return $this->_user;
      }
}
