<?php

namespace taskforce;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use yii\helpers\ArrayHelper;

class Geocoder
{
  const API_KEY = 'e666f398-c983-4bde-8f14-e3fec900592a';
  
  public function getGeocoderOptions (string $address): array
  {
    $apiUrl = 'https://geocode-maps.yandex.ru/';
    $apiController = '1.x';
    $optionsKey = 'response.GeoObjectCollection.featureMember';
    $addressKey = 'GeoObject.name';
    $coordinatesKey = 'GeoObject.Point.pos';
    $cityKey = 'GeoObject.metaDataProperty.GeocoderMetaData.Address.Components';
    $fullAddressKey = 'GeoObject.metaDataProperty.GeocoderMetaData.text';

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
        $address = ArrayHelper::getValue($value, $addressKey);
        $location = ArrayHelper::getValue ($value, $cityKey);
        $city = '';
        foreach($location as $element){
          if (array_search('locality', $element)){
            $city = $element['name'];
          }
        }
        if (!$city)
        {
          $city = $address;
        }

        $result[] = [
          'autocomplete' => ArrayHelper::getValue($value, $fullAddressKey),
          'address' => $address,
          'lat' => $latLong['0'],
          'long' => $latLong['1'],
          'city' => $city,
        ];
      }
    }catch(RequestException $e)
    {
      $result = [];
    }

    return $result;
  }
}