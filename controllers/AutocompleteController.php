<?php

namespace app\controllers;

use yii\web\Controller;
use taskforce\Geocoder;

class AutocompleteController extends Controller
{  
  public function actionIndex($address)
  {    
    return $this->asJson(Geocoder::getGeocoderOptions($address));
  }
}
