<?php

namespace app\controllers;

use yii\web\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use yii\helpers\ArrayHelper;

const API_KEY = 'e666f398-c983-4bde-8f14-e3fec900592a';
const API_URL = 'https://geocode-maps.yandex.ru/';
const API_CONTROLLER = '1.x';
const GEOCODER_OPTIONS_KEY = 'response.GeoObjectCollection.featureMember';
const GEOCODER_ADDRESS_KEY = 'GeoObject.metaDataProperty.GeocoderMetaData.text';
const GEOCODER_COORDINATES_KEY = 'GeoObject.Point.pos';

class AutocompleteController extends Controller
{

  public static function getGeocoderOptions ($address) {

    $client = new Client([
      'base_uri' => API_URL,
    ]);
  
    $response = $client->request('GET', API_CONTROLLER, [
      'query' =>
      [
        'apikey' => API_KEY,
        'geocode' => $address,
        'format' => 'json',
        'results' => '5'
      ]
    ]);
    try {  
      $content = $response->getBody()->getContents();
      $response = json_decode($content, true);
      $options = ArrayHelper::getValue($response, GEOCODER_OPTIONS_KEY);    
      $result = [];
      foreach ($options as $value) {
        $latLong = explode(' ', ArrayHelper::getValue($value, GEOCODER_COORDINATES_KEY));
        $result[] = [
          'address' => ArrayHelper::getValue($value, GEOCODER_ADDRESS_KEY),
          'lat' => $latLong['0'],
          'long' => $latLong['1'],
        ];
      }
    }catch(RequestException $e)
    {
      $result = [];
    }

    return $result;
  }
  public function actionIndex($address)
  {    
    return $this->asJson(self::getGeocoderOptions($address));
  }
}
