<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

?>
<main>
  <div class="landing-top checkbox-wrapper">
            <?php $form = ActiveForm::begin(
          [     
            'fieldConfig' => [
              'template' => "{label}{input}",
              'options' => ['tag' => 'p'],
              'inputOptions' => ['class' => 'enter-form-email input input-middle'],
              'labelOptions' => ['class' => 'form-modal-description'],
            ],
          ]
          );

          echo $form->field($model, 'isexecutor')->checkbox()->label(Html::encode($model->name) . ', собираетесь ли вы откликаться на заказы');
          ?>
            <button class="button" type="submit">Создать аккаунт</button>
            <?php ActiveForm::end(); ?>
           </div>
  </form>
</main>