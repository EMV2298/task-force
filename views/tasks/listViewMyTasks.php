<?php

use yii\helpers\Html;

?>
<div class="task-card">
  <div class="header-task">
    <a href="<?= Yii::$app->urlManager->createUrl(['tasks/view/', 'id' => $model->id]); ?>" class="link link--block link--big"><?= Html::encode($model->title) ?></a>
    <p class="price price--task"><?= Html::encode($model->budget) ?> ₽</p>
  </div>
  <p class="info-text"><?= Yii::$app->formatter->asRelativeTime(HTML::encode($model->dt_add)) ?></p>
  <p class="task-text"><?= Html::encode($model->description ?? '') ?></p>
  <div class="footer-task">
    <p class="info-text town-text"><?= Html::encode($model->address ?? '') ?></p>
    <p class="info-text category-text"><?= Html::encode($model->category->name) ?></p>
    <a href="<?= Yii::$app->urlManager->createUrl(['tasks/view/', 'id' => $model->id]); ?>" class="button button--black">Смотреть Задание</a>
  </div>
</div>