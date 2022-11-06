<?php

namespace app\controllers;

use Yii;

class DownloadController extends SecuredController
{
  public function actionIndex($filename)
  {
    $filePath = Yii::getAlias('@webroot/uploads/tasks-files');
    if (file_exists("$filePath/$filename")) 
    {
      Yii::$app->response->sendFile("$filePath/$filename", $filename);
    }
  }
}
