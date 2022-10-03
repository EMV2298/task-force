<?php

namespace app\controllers;

use app\models\form\LoginForm;
use Yii;


class LoginController extends NotSecuredController
{
  public function actionIndex()
  {
    $model = new LoginForm();

    $this->layout = 'landing';

    if (Yii::$app->request->getIsPost()) {

      $model->load(Yii::$app->request->post());

      if ($model->validate()) {

        $user = $model->getUser();
        Yii::$app->user->login($user);

        return $this->goHome();
      }
    }
    return $this->render('landing', ['model' => $model]);
  }
}
