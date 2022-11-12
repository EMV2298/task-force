<?php

namespace app\models\form;

use app\models\Users;
use taskforce\Files;
use yii\base\Model;

class VkRegistration extends Model
{
  public $vkId;
  public $name;
  public $email;
  public $dob;
  public $city;
  public $avatar;
  public $isexecutor;

  private $user;

  public function rules()
  {
    return
      [
        [['name', 'email', 'city'], 'required'],
        ['email', 'unique', 'targetClass' => Users::class, 'targetAttribute' => ['email' => 'email']],
        ['isexecutor', 'safe'],
      ];
  }

  public function getUser()
  {
    $this->user = Users::findOne(['email' => $this->email]);

    return $this->user;
  }
  
  public function saveUser()
  {
    $user = new Users();
    $user->vk_id = $this->vkId;
    $user->email = $this->email;
    $user->name = $this->name;
    $user->dob = $this->dob;
    $user->city_id = $this->city;
    $user->is_executor = $this->isexecutor;
    if($this->avatar)
    {
      $user->avatar = Files::uploadUrlAvatar($this->avatar);
    }
    $user->save();    
  }
}
