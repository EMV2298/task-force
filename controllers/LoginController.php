<?php

namespace app\controllers;

use app\models\Cities;
use app\models\form\LoginForm;
use app\models\form\VkRegistration;
use app\models\Users;
use taskforce\business\VkoAuth2;
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

    public function actionVk()
    {
        $vkOauth = new VkoAuth2();
        $vkOauth->openVk();
    }

    public function actionVkoauth($code)
    {
        $vkOauth = new VkoAuth2();
        $token = $vkOauth->getToken($code);
        $user = Users::findOne(['email' => $token['email']]);

        if ($user) {
            if (!$user->vk_id) {
                $user->vk_id = $token['user_id'];
                $user->save();
            }
            Yii::$app->user->login($user);

            Yii::$app->response->redirect(['tasks']);
        } else {
            Yii::$app->response->redirect(['login/vkreg', 'token' => json_encode($token)]);
        }
    }

    public function actionVkreg($token)
    {
        $token = json_decode($token, true);
        $vkOauth = new VkoAuth2();
        $userData = $vkOauth->getUserData($token);

        if ($userData) {
            $model = new VkRegistration();
            $model->vkId = $userData['id'];
            $model->name = $userData['first_name'];
            $model->email = $token['email'];
            $model->dob = Yii::$app->formatter->asDate($userData['bdate'], 'yyyy-MM-dd');
            $model->city = Cities::getCityId($userData['city']['title']);
            if (Yii::$app->request->getIsPost()) {
                $model->load(Yii::$app->request->post());

                if ($userData[VkoAuth2::PHOTO_KEY]) {
                    $model->avatar = $userData[VkoAuth2::PHOTO_KEY];
                }

                if ($model->validate()) {
                    $model->saveUser();
                    $user = $model->getUser();

                    Yii::$app->user->login($user);

                    Yii::$app->response->redirect(['tasks']);
                }
            }
            $this->layout = 'landing';
            return $this->render('setUserRoles', ['model' => $model]);
        }
    }
}
