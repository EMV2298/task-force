<?php

use taskforce\business\Task;
use yii\helpers\Html;

?>
<div class="response-card">
    <img class="customer-photo" src="<?= Html::encode($model->executor->avatar ?? ''); ?>" width="146" height="156" alt="Фото заказчиков">
    <div class="feedback-wrapper">
        <a href="#" class="link link--block link--big"><?= HTML::encode($model->users->name ?? ''); ?></a>
        <div class="response-wrapper">
            <div class="stars-rating small"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
            <p class="reviews"><?= count($model->executor->executorReviews); ?> отзыва</p>
        </div>
        <p class="response-message">
            <?= HTML::encode($model->message ?? ''); ?>
        </p>

    </div>
    <div class="feedback-wrapper">
        <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime(HTML::encode($model->dt_add)) ?></span></p>
        <p class="price price--small"><?= HTML::encode($model->price ?? ''); ?> ₽</p>
    </div>
    <?php if ($model->task->customer_id === Yii::$app->user->getId() && empty($model->denied) && $model->task->status === Task::STATUS_NEW): ?>
        <div class="button-popup">
            <a href="<?= Yii::$app->urlManager->createUrl(['tasks/accept', 'task' => $model->task->id, 'user' => $model->executor_id]) ?>"
             class="button button--blue button--small">Принять</a>
            <a href="<?= Yii::$app->urlManager->createUrl(['tasks/denied', 'task' => $model->task->id, 'user' => $model->executor_id]) ?>"
             class="button button--orange button--small">Отказать</a>
        </div>
    <?php endif; ?>
</div>