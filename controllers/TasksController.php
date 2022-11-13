<?php

namespace app\controllers;

use app\models\form\AddOffer;
use app\models\form\AddTask;
use app\models\form\FilterTasks;
use app\models\form\ReviewForm;
use app\models\Offers;
use app\models\Tasks;
use app\models\Users;
use taskforce\business\actions\Cancel;
use taskforce\business\actions\Reject;
use taskforce\business\Task;
use taskforce\business\User;
use taskforce\exception\TaskActionException;
use taskforce\Geocoder;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class TasksController extends SecuredController
{
    public const PAGE_SIZE = 5;

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

        $dataProvider = new ActiveDataProvider([
          'query' => $model->getTasks(),
          'pagination' => [
            'pageSize' => self::PAGE_SIZE,
          ],
        ]);

        return $this->render('tasks', ['dataProvider' => $dataProvider, 'model' => $model]);
    }

    public function actionView(int $id)
    {
        $userId = Yii::$app->user->getId();
        $task = Tasks::findOne($id);
        $offerModel = new AddOffer();
        $reviewModel = new ReviewForm();

        $dataProvider = new ActiveDataProvider([
          'query' => Offers::find()->andFilterWhere(['task_id' => $task->id]),
          'sort' => ['defaultOrder' => ['dt_add' => SORT_DESC]]
        ]);
        if ($task->customer_id !== $userId) {
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
        if (Yii::$app->request->getIsPost()) {
            $model->load(Yii::$app->request->post());
            if ($model->validate()) {
                if ($model->address && !$model->lat && !$model->long) {
                    $geocoder = new Geocoder();
                    $locations = $geocoder->getGeocoderOptions($model->address);
                    $model->lat = $locations[0]['lat'];
                    $model->long = $locations[0]['long'];
                    $model->address = $locations[0]['address'];
                }
                $taskId = $model->saveTask();
                if ($taskId) {
                    $this->redirect("view/{$taskId}");
                }
            }
        }
        return $this->render('add', ['model' => $model]);
    }

    public function actionDenied($task, $user)
    {
        $customer = Tasks::findOne($task)->customer_id;
        if ($customer === Yii::$app->user->getId()) {
            $offer = Offers::find()->andFilterWhere(['task_id' => $task, 'executor_id' => $user])->one();
            $offer->denied = 1;
            $offer->save();
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionAccept($task, $user)
    {
        $task = Tasks::findOne($task);

        if ($task->customer_id === Yii::$app->user->getId()) {
            if ($task->setExecutor($user)) {
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        throw new TaskActionException('Не удалось совершить дейсвие');
    }

    public function actionOffer()
    {
        $offerModel = new AddOffer();

        if (Yii::$app->request->getIsPost()) {
            $offerModel->load(Yii::$app->request->post());

            if ($offerModel->validate()) {
                if ($offerModel->saveOffer()) {
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    throw new TaskActionException('Действие не доступно');
                }
            }
        }
    }

    public function actionReview()
    {
        $review = new ReviewForm();
        if (Yii::$app->request->getIsPost()) {
            $review->load(Yii::$app->request->post());
            if ($review->validate()) {
                if ($review->saveReview()) {
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    throw new TaskActionException('Не удалось завершить задание');
                }
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionReject($task)
    {
        $task = Tasks::findOne($task);
        if ($task) {
            $checkAccess = new Task($task->customer_id, $task->executor_id, $task->status);
            $action = $checkAccess->getAvailableActions(Yii::$app->user->getId(), $task->id);
            if ($action instanceof Reject) {
                $task->reject();
                $user = Users::findOne($task->executor_id);
                $user->updateRating();

                return $this->redirect(Yii::$app->request->referrer);
            }
        }
        throw new TaskActionException('Действие не доступно');
    }

    public function actionCancel($task)
    {
        $task = Tasks::findOne($task);
        if ($task) {
            $checkAccess = new Task($task->customer_id, $task->executor_id, $task->status);
            $action = $checkAccess->getAvailableActions(Yii::$app->user->getId(), $task->id);
            if ($action instanceof Cancel) {
                $task->cancel();
                return $this->redirect(Yii::$app->request->referrer);
            }
            throw new TaskActionException('Действие не доступно');
        }
    }

    public function actionMy($type)
    {
        $taskStatuses = Task::getTaskStatusesForMytask($type);

        if (count($taskStatuses) > 0) {
            $user = Yii::$app->user->getIdentity();
            $userSqlRole = User::getSqlRole($user->is_executor);

            if (!$user->is_executor || $type !== Task::STATUS_NEW) {
                $dataProvider = new ActiveDataProvider([
                  'query' => Tasks::find()->where([$userSqlRole => $user->id])
                    ->andFilterWhere(['IN', 'status', $taskStatuses]),
                  'pagination' => [
                    'pageSize' => self::PAGE_SIZE,
                  ],
                ]);
                $titles = [
                  Task::STATUS_NEW => 'Новые задания',
                  Task::STATUS_IN_PROGRESS => 'Задания в процессе',
                  Task::STATUS_DONE => 'Закрытые задания'
                ];

                return $this->render('my', ['dataProvider' => $dataProvider, 'type' => $type, 'title' => $titles[$type]]);
            }
        }
        throw new ForbiddenHttpException('Нет доступного действия');
    }
}
