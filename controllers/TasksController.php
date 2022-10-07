<?php

namespace app\controllers;

use app\models\form\AddTask;
use app\models\form\FilterTasks;
use app\models\Tasks;
use Yii;
use yii\web\NotFoundHttpException;

class TasksController extends SecuredController
{

  public function behaviors()
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => ['add'],
            'matchCallback' => function ($rule, $action) {

                $isExecutor = Yii::$app->user->getIdentity()->is_executor;

                return empty($isExecutor) ? false : true;
            }
        ];

        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

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

  public function actionAdd()
  {
    
    $model = new AddTask();
    if (Yii::$app->request->getIsPost()){
      $model->load(Yii::$app->request->post());
     if ($model->validate()){
        $model->saveTask();
        
        return Yii::$app->response->redirect(['tasks']);
     
     }
    }
    return $this->render('add', ['model' => $model]);
  }
}
