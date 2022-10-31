<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use taskforce\Geocoder;
use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class MainAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/style.css',
    ];
    public $js = [
        'https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js',
        'https://api-maps.yandex.ru/2.1/?apikey=' . Geocoder::API_KEY . '&lang=ru_RU',
        'js/main.js',
        '/js/map.js',        
        '/js/autoComplete.js',        
    ];
    public $depends = [
        'yii\web\YiiAsset',       
    ];
}
