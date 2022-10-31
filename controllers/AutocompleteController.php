<?php

namespace app\controllers;

use yii\web\Controller;
use taskforce\Geocoder;

class AutocompleteController extends Controller
{  
  public function actionIndex($address)
  {   
    $geocoder = new Geocoder();
    return $this->asJson($geocoder->getGeocoderOptions($address));
  }
}
