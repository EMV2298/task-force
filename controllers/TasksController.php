<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\Tasks;
use taskforce\business\Task;

class TasksController extends Controller {
  
  public function actionIndex() {
    $tasks = Tasks::find()
      ->where(['status' => Task::STATUS_NEW])
      ->all();    
    return $this->render('tasks', ['tasks' => $tasks]);
  }
}