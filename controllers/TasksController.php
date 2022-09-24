<?php

namespace app\controllers;

use app\models\form\FilterTasks;
use yii\web\Controller;
use app\models\Tasks;
use taskforce\business\Task;
use Yii;

class TasksController extends Controller
{

  public function actionIndex()
  {

    $model = new FilterTasks();

    if (Yii::$app->request->getIsPost()) {
      $model->load(Yii::$app->request->post());
    }

    $tasks = $model->getTasks();

    return $this->render('tasks', ['tasks' => $tasks, 'model' => $model]);
  }
}
