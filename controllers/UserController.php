<?php
namespace app\controllers;

use app\models\Users;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class UserController extends SecuredController
 {

  public function actionView($id) {

    $user = Users::findOne($id);
    if (!$user) {
      throw new NotFoundHttpException('Пользователь не найден');
    }
    return $this->render('view', ['user' => $user]);
  }

  public function actionLogout()
  {
    Yii::$app->user->logout();
    Yii::$app->response->redirect(['login']);
  }
}