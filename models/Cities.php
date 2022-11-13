<?php

namespace app\models;

use taskforce\Geocoder;
use Yii;

/**
 * This is the model class for table "cities".
 *
 * @property int $id
 * @property string $name
 * @property string $lat
 * @property string $lng
 *
 * @property Tasks[] $tasks
 * @property Users[] $users
 */
class Cities extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'lat', 'lng'], 'required'],
            [['lat', 'lng'], 'string'],
            [['name'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'lat' => 'Lat',
            'lng' => 'Lng',
        ];
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Tasks::class, ['city_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::class, ['city_id' => 'id']);
    }

    public static function getCities(): array
    {
        $cities = static::find()
            ->select('name')
            ->indexBy('id')
            ->column();
        return $cities;
    }

    public static function getCityId($cityName)
    {
        $city = self::findOne(['name' => $cityName]);
        if (!$city) {
            $geocoder = new Geocoder();
            $cityGeo = $geocoder->getGeocoderOptions($cityName);
            $city = new self();
            $city->name = $cityName;
            $city->lat = $cityGeo['0']['lat'];
            $city->lng = $cityGeo['0']['long'];
            $city->save();
        }
        return $city->id;
    }
}
