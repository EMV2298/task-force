<?php

use app\models\Categories;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<main class="main-content main-content--left container">
  <div class="left-menu left-menu--edit">
    <h3 class="head-main head-task">Настройки</h3>
    <ul class="side-menu-list">
      <li class="side-menu-item <?= Yii::$app->request->get('type') !== 'security' ? 'side-menu-item--active' : ''; ?>">
        <a href="<?= Yii::$app->urlManager->createUrl(['user/setting']); ?>" class="link link--nav">Мой профиль</a>
      </li>
      <li class="side-menu-item <?= Yii::$app->request->get('type') === 'security' ? 'side-menu-item--active' : ''; ?>">
        <a href="<?= Yii::$app->urlManager->createUrl(['user/setting/security']); ?>" class="link link--nav">Безопасность</a>
      </li>
    </ul>
  </div>
  <div class="my-profile-form">
    <?php
    $form = ActiveForm::begin([
      'method' => 'post',
      'fieldConfig' => ['template' => '{label}{input}{error}']
    ]); ?>

    <?php if (Yii::$app->request->get('type') !== 'security') : ?>

      <h3 class="head-main head-regular">Мой профиль</h3>
      <div class="photo-editing">
        <div>
          <p class="form-label">Аватар</p>
          <img class="avatar-preview" src="/uploads/user-avatar/<?= Html::encode(Yii::$app->user->getIdentity()->avatar); ?>" width="83" height="83">
        </div>
        <?= $form->field($model, 'avatar', ['template' => '{input}{label}'])
          ->fileInput(['id' => 'button-input', 'hidden' => 'hidden'])
          ->label('Сменить аватар', ['for' => 'button-input', 'class' => 'button button--black']); ?>
      </div>
      <?= $form->field($model, 'name')->textInput(); ?>
      <div class="half-wrapper">
        <?= $form->field($model, 'email')->input('email'); ?>
        <?= $form->field($model, 'dob')->input('date'); ?>
      </div>
      <div class="half-wrapper">
        <?= $form->field($model, 'phone')->input('tel'); ?>
        <?= $form->field($model, 'telegram')->input('text'); ?>
      </div>
      <?= $form->field($model, 'description')->textarea(); ?>
      <?= $form->field($model, 'categories')->checkboxList(
        Categories::getCategories(),
        ['class' => 'checkbox-profile', 'itemOptions' => ['labelOptions' => ['class' => 'control-label']]]
      ); ?>
    <?php else : ?>
      <?php
      if (!Yii::$app->user->getIdentity()->vk_id)
      {
      echo $form->field($model, 'old')->passwordInput();
      echo $form->field($model, 'new')->passwordInput();
      echo $form->field($model, 'repeat')->passwordInput();
      }
      echo $form->field($model, 'showContacts')->checkbox(['checked' => !empty(Yii::$app->user->getIdentity()->show_contacts)]);
      ?>
    <?php endif; ?>
    <input type="submit" class="button button--blue" value="Сохранить">
    <?php ActiveForm::end(); ?>

  </div>
</main>