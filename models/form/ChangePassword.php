<?php

namespace app\models\form;

use app\models\Users;
use Yii;
use yii\base\Model;

class ChangePassword extends Model
{
  public $old;
  public $new;
  public $repeat;
  public $showContacts;  

  public function attributeLabels()
  {
    return
    [
      'old' => 'Старый пароль',
      'new' => 'Новый пароль',
      'repeat' => 'Повторите новый пароль',
      'showContacts' => 'Показать контакты'
    ];
  }

  public function rules()
  {
    return
    [
      [['new', 'old', 'repeat'], 'checkVkId'],
      [['new', 'repeat'], 'string', 'max' => 150],
      [['repeat'], 'compare', 'compareAttribute' => 'new'],
      ['new', 'checkAccess'],
      ['old', 'checkPassword'],
      
    ];
  }

  public function checkPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Yii::$app->user->getIdentity();
            if (!Yii::$app->security->validatePassword($this->old, $user->password)) {
                $this->addError($attribute, 'Неправильный пароль');
            }
        }
    }
  public function checkVkId($attribute, $params)
  {
    $user = Yii::$app->user->getIdentity();
    if ($user->vk_id){
      $this->addError($attribute, 'Невозможно сменить пароль');
    }
  }

  public function checkAccess($attribute, $params)
  {
    if (!$this->old && $this->new)
    {
      $this->addError($attribute, 'Введите старый пароль');
    }
  }

  public function save()
  {
    $user = Users::findOne(Yii::$app->user->getId());
    
    if ($this->new)
    {
      $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->new);     
    }
    $user->show_contacts = $this->showContacts;

    return $user->save();
  }


}