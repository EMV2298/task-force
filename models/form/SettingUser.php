<?php

namespace app\models\form;

use app\models\Categories;
use app\models\ExecutorCategories;
use app\models\Users;
use taskforce\Files;
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
        [['name', 'email'], 'required'],
        ['name', 'string', 'max' => 20],
        ['email', 'email'],
        ['email', 'validateEmail'],
        ['telegram', 'string', 'max' => 64],
        ['phone', 'match', 'pattern' => '/^\d+$/'],
        ['phone', 'string', 'min' => 11, 'max' => 11],
        ['dob', 'date', 'format' => 'Y-m-d'],
        ['description', 'string', 'max' => 200 ],
        ['avatar', 'file', 'extensions' => ['png', 'jpg', 'gif']],
        ['categories', 'safe']
        ];
    }

    public function validateEmail($attribute)
    {
        $user = Yii::$app->user->getIdentity();
        if ($this->email !== $user->email) {
            $checkEmail = Users::findOne(['email' => $this->email]);

            if ($checkEmail) {
                $this->addError($attribute, 'Этот EMAIL уже занят');
            }
        }
    }



    public function save()
    {
        $user = Users::findOne(Yii::$app->user->getId());

        if ($this->name !== $user->name) {
            $user->name = $this->name;
        }

        if ($this->email !== $user->email) {
            $user->email = $this->email;
        }

        if ($this->dob !== $user->dob) {
            $user->dob = $this->dob;
        }

        if ($this->phone !== $user->phonenumber) {
            $user->phonenumber = $this->phone;
        }

        if ($this->telegram !== $user->telegram) {
            $user->telegram = $this->telegram;
        }

        if ($this->description !== $user->description) {
            $user->description = $this->description;
        }

        if ($this->avatar) {
            $user->avatar = Files::uploadUserAvatar($this->avatar);
        }

        if (is_array($this->categories) && count($this->categories) > 0) {
            $deleteCategory = ExecutorCategories::find()->where(['user_id' => $user->id])
            ->andFilterWhere(['NOT IN', 'category_id', $this->categories])->all();
            foreach ($deleteCategory as $delete) {
                $delete->delete();
            }

            foreach ($this->categories as $category) {
                $check = ExecutorCategories::find()->where(['user_id' => $user->id, 'category_id' => $category])->all();
                if (!$check) {
                    $newUserCategory = new ExecutorCategories();
                    $newUserCategory->user_id = $user->id;
                    $newUserCategory->category_id = $category;
                    $newUserCategory->save();
                }
            }
        } else {
            $deteteElements = ExecutorCategories::find()->where(['user_id' => $user->id])->all();
            foreach ($deteteElements as $element) {
                $element->delete();
            }
        }



        return $user->save();
    }
}
