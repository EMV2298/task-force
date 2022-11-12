<?php

namespace app\models\form;

use app\models\Reviews;
use app\models\Tasks;
use app\models\Users;
use taskforce\business\Task;
use taskforce\exception\TaskActionException;
use Yii;
use yii\base\Model;

class ReviewForm extends Model
{
  public $message;
  public $rating;
  public $taskId;

  public function attributeLabels()
  {
    return
      [
        'message' => 'Ваш комментарий',
        'rating' => 'Оценка работы'
      ];
  }

  public function rules()
  {
    return
      [
        ['rating', 'required'],
        ['message', 'string', 'max' => 100],
        ['rating', 'compare', 'compareValue' => 1, 'operator' => '>', 'type' => 'number'],
        ['rating', 'compare', 'compareValue' => 5, 'operator' => '<=', 'type' => 'number'],
        ['taskId', 'exist', 'targetClass' => Tasks::class, 'targetAttribute' => ['taskId' => 'id']],
      ];
  }

  public function saveReview()
  {
    $task = Tasks::findOne($this->taskId);
    if ($task->customer_id === Yii::$app->user->getId()) {
      $review = new Reviews();
      $review->message = $this->message;
      $review->rating = $this->rating;
      $review->task_id = $this->taskId;
      $review->customer_id = $task->customer_id;
      $review->executor_id = $task->executor_id;

      $task->status = Task::STATUS_DONE;

      $review->save();
      $task->save();

      $user = Users::findOne($task->executor_id);
      $user->updateRating();
      return true;
    } else

    throw new TaskActionException('Действие вам недоступно');
  }
}
