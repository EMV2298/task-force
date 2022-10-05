<?php

namespace app\models\form;

use app\models\Tasks;
use taskforce\business\Task;
use yii\base\Model;

class FilterTasks extends Model
{
  public $categories;
  public bool $noOffer = false;
  public bool $isDistant = false;
  public $period;

  const PERIODS =
  [
    'All time' => 'За все время',
    '1 HOUR' => '1 час',
    '1 DAY' => '1 день',
    '1 MONTH' => '1 месяц',    
  ];

  public function getTasks()
  {
    $tasks = Tasks::find();
    $tasks->joinWith('category');
    $tasks->leftJoin('offers', 'offers.task_id = tasks.id');
    $tasks->andFilterWhere(['status' => Task::STATUS_NEW]);

    if (is_array($this->categories) && count($this->categories) > 0) {
      $tasks->andFilterWhere(['category_id' => $this->categories]);
    }

    if ($this->noOffer) {
      $tasks->andWhere(['is', 'offers.task_id', new \yii\db\Expression('null')]);
    }

    if ($this->isDistant) {
      $tasks->andFilterWhere(['is', 'city_id', new \yii\db\Expression('null')]);
      $tasks->andFilterWhere(['is', 'location', new \yii\db\Expression('null')]);
    }

    if (isset(self::PERIODS[$this->period]) && $this->period !== 'All time') {
      $tasks->andWhere('tasks.dt_add >= NOW() - INTERVAL ' . $this->period);
    }

    return $tasks->all();
  }

  public function attributeLabels(): array
  {
    return [
      'categores' => 'Категории',
      'noOffer' => 'Без откликов',
      'time' => 'Период',
      'isDistant' => 'Удаленная Работа'
    ];
  }

  public function rules()
  {
    return [
      [['categories', 'period', 'isDistant', 'noOffer'], 'safe']
    ];
  }
}
