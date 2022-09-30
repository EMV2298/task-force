<?php

namespace app\controllers;

use app\models\Cities;
use app\models\form\FilterTasks;
use app\models\Tasks;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;

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

  public function actionView (int $id)
  {
    $task = Tasks::findOne($id);

    if (!$task) {
      throw new NotFoundHttpException('Задание не найдено');
    }

    return $this->render('view', ['task' => $task]);
  }
}
