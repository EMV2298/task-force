<?php

namespace app\controllers;

use app\models\form\FilterTasks;
use yii\web\Controller;
use Yii;

class TasksController extends Controller
{

  public function actionIndex()
  {

    $model = new FilterTasks();

    if (Yii::$app->request->getIsGet()) {
      $model->load(Yii::$app->request->get());
    }

    $tasks = $model->getTasks();

    return $this->render('tasks', ['tasks' => $tasks, 'model' => $model]);
  }
}
