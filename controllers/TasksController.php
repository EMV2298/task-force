<?php

namespace app\controllers;

use app\models\form\AddOffer;
use app\models\form\AddTask;
use app\models\form\FilterTasks;
use app\models\form\ReviewForm;
use app\models\Offers;
use app\models\Tasks;
use app\models\Users;
use taskforce\business\Task;
use taskforce\exception\TaskActionException;
use taskforce\Geocoder;
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
            'actions' => ['add', 'reviews', 'cancel', 'denied'],
            'matchCallback' => function ($rule, $action) {
                $isExecutor = Yii::$app->user->getIdentity()->is_executor;
                return empty($isExecutor) ? false : true;
            }
        ];
        $ruleOffer = [
          'allow' => false,
          'actions' => ['offer', 'reject', 'access'],
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
    $sum = Tasks::find()->where(['executor_id' => 4, 'status' => Task::STATUS_FAIL])->count('id');
    print($sum);
    $userId = Yii::$app->user->getId();
    $task = Tasks::findOne($id);
    $offerModel = new AddOffer();
    $reviewModel = new ReviewForm();

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

    return $this->render('view', ['task' => $task, 'dataProvider' => $dataProvider, 'offerModel' => $offerModel, 'reviewModel' => $reviewModel]);
  }
  
  public function actionAdd()
  {
    
    $model = new AddTask();
    if (Yii::$app->request->getIsPost()){
      $model->load(Yii::$app->request->post());
     if ($model->validate())
     {
        if($model->address && !$model->lat && !$model->long)
        {
          $geocoder = new Geocoder();
          $locations = $geocoder->getGeocoderOptions($model->address);
          $model->lat = $locations[0]['lat'];
          $model->long = $locations[0]['long'];
          $model->address = $locations[0]['address'];
        }
        $taskId = $model->saveTask();
        if($taskId)
        {
          $this->redirect("view/{$taskId}");
        }
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

  public function actionReview()
  {  
    
    $review = new ReviewForm();
    if (Yii::$app->request->getIsPost())
    {
      $review->load(Yii::$app->request->post());
      if($review->validate())
      {
        $review->saveReview();
        return $this->redirect(Yii::$app->request->referrer);
      }
    }
    throw new TaskActionException('Не удалось завершить задание');
  }

  public function actionReject($task)
  {
    $task = Tasks::findOne($task);
    if ($task && $task->executor_id === Yii::$app->user->getId() && $task->status === Task::STATUS_IN_PROGRESS)
    {
      $task->reject();
      $user = Users::findOne($task->executor_id);
      $user->updateRating();

      return $this->redirect(Yii::$app->request->referrer);
    }
    throw new TaskActionException('Действие не доступно');
  }

  public function actionCancel($task)
  {
    $task = Tasks::findOne($task);
    if ($task && $task->customer_id === Yii::$app->user->getId() && $task->status === Task::STATUS_NEW)
    {
      $task->cancel();
      return $this->redirect(Yii::$app->request->referrer);
    }
    throw new TaskActionException('Действие не доступно');
  }

  
}
