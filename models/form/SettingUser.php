<?php

namespace app\models\form;

use app\models\Categories;
use app\models\ExecutorCategories;
use app\models\Users;
use Yii;
use yii\base\Model;

class SettingUser extends Model
{
  public $name;
  public $email;
  public $dob;
  public $phone;
  public $telegram;
  public $description;
  public $categories;
  public $avatar;
  
  public function attributeLabels()
  {
    return
    [
      'name' => 'Ваше имя',
      'email' => 'Email',
      'dob' => 'День рождения',
      'phone' => 'Номер телефона',
      'telegram' => 'Telegram',
      'description' => 'Информация о себе',
      'categories' => 'Выбор специализаций',
      'avatar' => 'Аватар'
    ];
  }

  public function rules()
  {
    return
    [
    ['name', 'string', 'max' => 20],
    ['email', 'email'],
    ['email', 'unique', 'targetClass' => Users::class, 'targetAttribute' => ['email' => 'email']],
    ['telegram', 'string', 'max' => 64],
    ['phone', 'match', 'pattern' => '/^\d+$/'],
    ['phone', 'string', 'min' => 11, 'max' => 11],
    ['dob', 'date', 'format' => 'Y-m-d'],
    ['description', 'string', 'max' => 200 ],
    ['avatar', 'file', 'extensions' => ['png', 'jpg', 'gif'], 'maxSize' => 1024*1024],
    ['categories', 'safe']
    ];
  }

  public function save()
  {
    $user = Users::findOne(Yii::$app->user->getId());

    if ($this->name)
    {
      $user->name = $this->name;      
    }

    if ($this->email)
    {
      $user->email = $this->email;      
    }

    if ($this->dob)
    {
      $user->dob = $this->dob;      
    }

    if ($this->phone)
    {
      $user->phonenumber = $this->phone;      
    }

    if ($this->telegram)
    {
      $user->telegram = $this->telegram;      
    }

    if ($this->description)
    {
      $user->description = $this->description;      
    }

    if ($this->avatar)
    {     
      $name = uniqid('user-avatar') . '.' . $this->avatar->getExtension();
      if ($this->avatar->saveAs('@webroot/uploads/user-avatar/' . $name)) {
          $user->avatar = $name;
      }      
    }
    
    if (count($this->categories) > 0)
    {
      foreach($this->categories as $category)
      {
        $newUserCategory = new ExecutorCategories();
        $newUserCategory->user_id = $user->id;
        $newUserCategory->category_id = $category;
        $newUserCategory->save();
      }
    }
    
    return $user->save();
  }
}

