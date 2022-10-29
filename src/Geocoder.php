<?php

namespace taskforce;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use yii\helpers\ArrayHelper;

class Geocoder
{
  const API_KEY = 'e666f398-c983-4bde-8f14-e3fec900592a';
  
  public static function getGeocoderOptions ($address)
  {
    $apiUrl = 'https://geocode-maps.yandex.ru/';
    $apiController = '1.x';
    $optionsKey = 'response.GeoObjectCollection.featureMember';
    $addressKey = 'GeoObject.metaDataProperty.GeocoderMetaData.text';
    $coordinatesKey = 'GeoObject.Point.pos';

    $client = new Client([
      'base_uri' => $apiUrl,
    ]);
  
    $response = $client->request('GET', $apiController, [
      'query' =>
      [
        'apikey' => self::API_KEY,
        'geocode' => $address,
        'format' => 'json',
        'results' => '5'
      ]
    ]);
    try {  
      $content = $response->getBody()->getContents();
      $response = json_decode($content, true);
      $options = ArrayHelper::getValue($response, $optionsKey);    
      $result = [];
      foreach ($options as $value) {
        $latLong = explode(' ', ArrayHelper::getValue($value, $coordinatesKey));
        $result[] = [
          'address' => ArrayHelper::getValue($value, $addressKey),
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
}