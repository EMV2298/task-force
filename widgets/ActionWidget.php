<?php

namespace app\widgets;

use taskforce\business\actions\Actions;
use yii\base\Widget;
use yii\helpers\Html;

class ActionWidget extends Widget
{
    public Actions $action;

    public function init()
    {

    }
    
    public function run()
    {      
        return Html::a(Html::encode($this->action->getActionName()), '#', ['class' => $this->action->getClass(), 'data-action' => $this->action->getActionData()]);
    }
}