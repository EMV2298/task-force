<?php

namespace app\models\form;

use app\models\Cities;
use app\models\Users;
use Yii;
use yii\base\Model;


class Registration extends Model {

  public $username;
  public $email;
  public $city;
  public $password;
  public $repeatPassword;
  public $isExecutor;

  public function attributeLabels(): array
  {
    return [
      'username' => 'Ваше имя',
      'email' => 'Email',
      'city' => 'Город',
      'password' => 'Пароль',
      'repeatPassword' => 'Повтор пароля',
      'isExecutor' => ' я собираюсь откликаться на заказы',
    ];
  }

  public function rules()
  {
    return [
      [['username'], 'trim'],
      [['username', 'email', 'password', 'repeatPassword', 'city'], 'required'],
      [['username', 'email'], 'string', 'max' => 150],
      ['email', 'email'],
      ['email', 'unique', 'targetClass' => Users::class, 'targetAttribute' => ['email' => 'email']],
      ['city', 'exist', 'targetClass' => Cities::class, 'targetAttribute' => ['city' => 'id']],
      [['password', 'repeatPassword'], 'string', 'max' => 150],
      [['repeatPassword'], 'compare', 'compareAttribute' => 'password'],
      ['isExecutor', 'boolean']      
    ];
  }

  public function saveUser()
  {   
      $user = new Users();
      $user->email = $this->email;
      $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
      $user->name = $this->username;
      $user->city_id = $this->city;
      $user->is_executor = $this->isExecutor;

      return $user;
  }
}
