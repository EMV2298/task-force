<?php
namespace app\controllers;

use app\models\Categories;
use app\models\form\ChangePassword;
use app\models\form\SettingUser;
use app\models\Users;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

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

  public function actionSetting()
  {
    $type = Yii::$app->request->get('type');

    $model = $type === 'security' ? new ChangePassword() : new SettingUser();
   
    if (Yii::$app->request->getIsPost())
    {
      $model->load(Yii::$app->request->post());

      if (isset($model->avatar))
      {
        $model->avatar = UploadedFile::getInstance($model, 'avatar');
      }
      
      if($model->validate())
      {
        if ($model->save())
        {
          Yii::$app->response->redirect(["user/view/" . Yii::$app->user->getId()]);
        }        
      }
    }

    return $this->render('setting', ['model' => $model]);
  }
  
}
