<?php

namespace app\controllers;


use app\models\form\AddOffer;
use app\models\form\AddTask;
use app\models\form\FilterTasks;
use app\models\Offers;
use app\models\Tasks;
use taskforce\exception\TaskActionException;
use Yii;
use yii\data\ActiveDataProvider;
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
        $ruleOffer = [
          'allow' => false,
          'actions' => ['offer'],
          'matchCallback' => function ($rule, $action) {
              $isExecutor = Yii::$app->user->getIdentity()->is_executor;
              return empty($isExecutor) ? true : false;
          }
        ];

        
        array_unshift($rules['access']['rules'], $ruleOffer);
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
    $userId = Yii::$app->user->getId();
    $task = Tasks::findOne($id);
    $offerModel = new AddOffer();

    $dataProvider = new ActiveDataProvider([
      'query' => Offers::find()->andFilterWhere(['task_id' => $task->id]),      
      'sort' => ['defaultOrder' => ['dt_add' => SORT_DESC]]
  ]);
  if ($task->customer_id !== $userId){
    $dataProvider->query->andFilterWhere(['executor_id' => $userId]);
  }

    if (!$task) {
      throw new NotFoundHttpException('Задание не найдено');
    }

    return $this->render('view', ['task' => $task, 'dataProvider' => $dataProvider, 'offerModel' => $offerModel]);
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

  public function actionDenied($task, $user)
  {
    
    $customer = Tasks::findOne($task)->customer_id;
    if ($customer === Yii::$app->user->getId())
    {
      $offer = Offers::find()->andFilterWhere(['task_id' => $task, 'executor_id' => $user])->one();
      $offer->denied = 1;
      $offer->save();
      return $this->redirect(Yii::$app->request->referrer);
    }
  }

  public function actionAccess($task, $user)
  {
    $task = Tasks::findOne($task);

    if ($task->customer_id === Yii::$app->user->getId())
    {      
      if($task->addExecutor($user))
      {
        return $this->redirect(Yii::$app->request->referrer);
      }
    }

    throw new TaskActionException('Не удалось совершить дейсвие');

  }

  public function actionOffer()
  {
    $offerModel = new AddOffer();

    if (Yii::$app->request->getIsPost())
    {
      $offerModel->load(Yii::$app->request->post()); 

      if ($offerModel->validate())
      {
        if($offerModel->saveOffer())
        {
          return $this->redirect(Yii::$app->request->referrer);
        }
      }
    }
    throw new TaskActionException('Действие не доступно');
    
  }

}
