<?php

namespace taskforce\business;

use VK\Client\VKApiClient;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;
use Yii;

class VkoAuth2 
{
    const PHOTO_KEY = 'photo_200_orig';
    const DEFAUL_PHOTO = 'https://vk.com/images/camera_200.png';
        
    private $client_id = 51457366;
    private $client_secret = 'txBaEq6HECm8fAtxaR2g';
    private $redirect_uri = 'http://taskforce/login/vkoauth'; 
    private $display = VKOAuthDisplay::POPUP;
    private $scope = [VKOAuthUserScope::WALL, VKOAuthUserScope::EMAIL];
    private $fields = ['city', 'email', self::PHOTO_KEY, 'first_name', 'bdate'];

    public function openVk()
    {
      $oauth = new VKOAuth();
      $browser_url = $oauth->getAuthorizeUrl(VKOAuthResponseType::CODE, $this->client_id, $this->redirect_uri, $this->display, $this->scope);
      Yii::$app->response->redirect($browser_url);
    }

    public function getToken($code)
    {
      $oauth = new VKOAuth();
      $response = $oauth->getAccessToken($this->client_id, $this->client_secret, $this->redirect_uri, $code);
      
      return $response;
    }
    
    public function getUserData($vkResponse)
    {
      $vk = new VKApiClient();
      $access_token = $vkResponse['access_token'];
      $user_id = $vkResponse['user_id'];    
      
      $responses = $vk->users()->get($access_token, array(
      'user_ids' => $user_id,
      'fields' => $this->fields,
      ));

      $response = $responses[0];

      if ($response[self::PHOTO_KEY] === self::DEFAUL_PHOTO)
      {
        $response[self::PHOTO_KEY] = '';
      }

      return $response;
    }

  
}