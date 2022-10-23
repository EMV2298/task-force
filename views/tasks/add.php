<?php

use app\models\Categories;
use yii\widgets\ActiveForm;

$this->registerJsFile('https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js');
$this->registerJsFile('/js/autoComplete.js');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.02.min.css');

?>
<main class="main-content main-content--center container">
    <div class="add-task-form regular-form">
    <?php
                    $form = ActiveForm::begin([
                    'method' => 'post',
                    'id' => 'add-task',
                    'fieldConfig' => [
                      'template' => '{label}{input}{error}',
                      'labelOptions' => ['class' => 'control-label']
                      ]
                ]);?>
            <h3 class="head-main head-main">Публикация нового задания</h3>

                <?php
                  echo $form->field($model, 'title');
                  echo $form->field($model, 'description')->textarea();
                  echo $form->field($model, 'category')->dropDownList(Categories::getCategories());
                  echo $form->field($model, 'address')->textInput(['class' => 'location-icon']);
                  echo $form->field($model, 'lat', ['template' => '{input}'])->hiddenInput();
                  echo $form->field($model, 'long', ['template' => '{input}'])->hiddenInput();                  
                ?>

            <div class="half-wrapper">
              <?php
                echo $form->field($model, 'price')->textInput(['class' => 'budget-icon']);
                echo $form->field($model, 'date')->textInput(['type' => 'date']);
              ?>
            </div>

            <p class="form-label">Файлы</p>
            <?php
              echo $form->field($model, 'files[]', 
              ['template' => '<div class="new-file">Добавить новый файл{input}</div>'])
              ->fileInput(['multiple' => 'multiple', 'style' => 'opacity:0; position: absolute;']);
            ?>

            <input type="submit" class="button button--blue" value="Опубликовать">
            <?php ActiveForm::end(); ?>
    </div>
</main>