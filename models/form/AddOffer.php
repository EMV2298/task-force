<?php

namespace app\models\form;

use app\models\Offers;
use app\models\Tasks;
use taskforce\business\Task;
use taskforce\exception\TaskActionException;
use Yii;
use yii\base\Model;

class AddOffer extends Model
{
  public $message;
  public $price;
  public $taskId;

  public function attributeLabels()
  {
    return
      [
        'message' => 'Ваш комментарий',
        'price' => 'Стоимость',
      ];
  }

  public function rules()
  {
    return
      [
        ['taskId', 'required'],
        ['message', 'string', 'max' => 100],
        ['price', 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number'],
        ['taskId', 'exist', 'targetClass' => Tasks::class, 'targetAttribute' => ['taskId' => 'id']],
      ];
  }

  public function saveOffer()
  {
    $userId = Yii::$app->user->getId();
    $offer = Offers::find()->andFilterWhere(['task_id' => $this->taskId, 'executor_id' => $userId])->one();
    if (!$offer) {
      $newOffer = new Offers();
      $newOffer->message = $this->message;
      $newOffer->executor_id = $userId;
      $newOffer->task_id = $this->taskId;
      $newOffer->price = $this->price;

      return $newOffer->save();
    }

    return false;
  }
}
