<?php

use app\models\Cities;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

?>
<main class="container container--registration">
    <div class="center-block">
        <div class="registration-form regular-form">
            <?php
                    $form = ActiveForm::begin([
                    'method' => 'post',
                    'id' => 'registration',
                    'fieldConfig' => ['template' => '{label}{input}{error}']
                ]);?>

                <h3 class="head-main head-task">Регистрация нового пользователя</h3>
                
                <?= $form->field($model, 'username');?>          

                <div class="half-wrapper">                  
                  <?php 
                    echo $form->field($model, 'email');
                    echo $form->field($model, 'city')->dropDownList(Cities::getCities());     ?>                
                </div>
                <div class="half-wrapper">
                  <?=$form->field($model, 'password'); ?>
                </div>
                <div class="half-wrapper">
                  <?=$form->field($model, 'repeatPassword'); ?>
                </div>
                  <?=$form->field($model, 'isExecutor', ['template' => '{input}'])
                  ->checkbox(['labelOptions' => ['class' => 'control-label checkbox-label']]);
                  ?>
                <input type="submit" class="button button--blue" value="Создать аккаунт">                      
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</main>