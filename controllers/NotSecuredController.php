<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

abstract class NotSecuredController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function () {
                    $this->goHome();
                },
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?']
                    ]
                ]
            ]
        ];
    }
}
