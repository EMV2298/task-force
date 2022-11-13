<?php

namespace app\controllers;

use app\models\form\Registration;
use ErrorException;
use Yii;

class RegistrationController extends NotSecuredController
{
    public function actionIndex()
    {
        $model = new Registration();

        if (Yii::$app->request->getIsPost()) {
            $model->load(Yii::$app->request->post());

            if ($model->validate()) {
                if (!$model->saveUser()->save()) {
                    throw new ErrorException('Не удалось сохранить данные');
                }
                Yii::$app->response->redirect(['tasks']);
            }
        }

        return $this->render('registration', ['model' => $model]);
    }
}
