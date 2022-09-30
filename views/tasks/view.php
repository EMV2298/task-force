<?php

use taskforce\business\Task;
use yii\helpers\Html;

?>
<main class="main-content container">
    <div class="left-column">
        <div class="head-wrapper">
            <h3 class="head-main"><?= HTML::encode($task->title ?? ''); ?></h3>
            <p class="price price--big"><?= HTML::encode($task->budget ?? ''); ?>₽</p>
        </div>
        <p class="task-description"><?= HTML::encode($task->description ?? ''); ?></p>
        <?php if ($task->status === Task::STATUS_NEW): ?>
        <a href="#" class="button button--blue action-btn" data-action="act_response">Откликнуться на задание</a>
        <?php endif; ?>
        <?php if ($task->status === Task::STATUS_IN_PROGRESS): ?>
        <a href="#" class="button button--orange action-btn" data-action="refusal">Отказаться от задания</a>
        <?php endif; ?>
        <?php if ($task->status === Task::STATUS_IN_PROGRESS): ?>
        <a href="#" class="button button--pink action-btn" data-action="completion">Завершить задание</a>
        <?php endif; ?>
        <div class="task-map">
            <img class="map" src="/img/map.png" width="725" height="346" alt="Новый арбат, 23, к. 1">
            <p class="map-address town"><?= HTML::encode($task->city->name ?? ''); ?></p>
            <p class="map-address">Новый арбат, 23, к. 1</p>
        </div>
        <?php if (count($task->offers) > 0): ?>
        <h4 class="head-regular">Отклики на задание</h4>
        <?php foreach ($task->offers as $offer) : ?>
            <div class="response-card">
                <img class="customer-photo" src="<?= HTML::encode($offer->executor->avatar ?? ''); ?>" width="146" height="156" alt="Фото заказчиков">
                <div class="feedback-wrapper">
                    <a href="#" class="link link--block link--big"><?= HTML::encode($offer->executor->name ?? ''); ?></a>
                    <div class="response-wrapper">
                        <div class="stars-rating small"><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span class="fill-star">&nbsp;</span><span>&nbsp;</span></div>
                        <p class="reviews"><?= count($offer->executor->executorReviews); ?> отзыва</p>
                    </div>
                    <p class="response-message">
                        <?= HTML::encode($offer->message ?? ''); ?>
                    </p>

                </div>
                <div class="feedback-wrapper">
                    <p class="info-text"><span class="current-time"><?= Yii::$app->formatter->asRelativeTime(HTML::encode($offer->dt_add)) ?></span></p>
                    <p class="price price--small"><?= HTML::encode($offer->price ?? ''); ?> ₽</p>
                </div>
                <div class="button-popup">
                    <a href="#" class="button button--blue button--small">Принять</a>
                    <a href="#" class="button button--orange button--small">Отказать</a>
                </div>
            </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="right-column">
        <div class="right-card black info-card">
            <h4 class="head-card">Информация о задании</h4>
            <dl class="black-list">
                <dt>Категория</dt>
                <dd><?= HTML::encode($task->category->name ?? ''); ?></dd>
                <dt>Дата публикации</dt>
                <dd><?= Yii::$app->formatter->asRelativeTime(HTML::encode($task->dt_add)) ?></dd>
                <?php if ($task->date_completion) : ?>
                    <dt>Срок выполнения</dt>
                    <dd><?= Yii::$app->formatter->asDatetime(HTML::encode($task->date_completion), "d MMMM, h:m") ?></dd>
                <?php endif; ?>
                <dt>Статус</dt>
                <dd><?= Task::getAllStatuses()[$task->status] ?? ''; ?></dd>
            </dl>
        </div>
        <div class="right-card white file-card">
            <h4 class="head-card">Файлы задания</h4>
            <ul class="enumeration-list">
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--clip">my_picture.jpg</a>
                    <p class="file-size">356 Кб</p>
                </li>
                <li class="enumeration-item">
                    <a href="#" class="link link--block link--clip">information.docx</a>
                    <p class="file-size">12 Кб</p>
                </li>
            </ul>
        </div>
    </div>
</main>
<section class="pop-up pop-up--refusal pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Отказ от задания</h4>
        <p class="pop-up-text">
            <b>Внимание!</b><br>
            Вы собираетесь отказаться от выполнения этого задания.<br>
            Это действие плохо скажется на вашем рейтинге и увеличит счетчик проваленных заданий.
        </p>
        <a class="button button--pop-up button--orange">Отказаться</a>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<section class="pop-up pop-up--completion pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Завершение задания</h4>
        <p class="pop-up-text">
            Вы собираетесь отметить это задание как выполненное.
            Пожалуйста, оставьте отзыв об исполнителе и отметьте отдельно, если возникли проблемы.
        </p>
        <div class="completion-form pop-up--form regular-form">
            <form>
                <div class="form-group">
                    <label class="control-label" for="completion-comment">Ваш комментарий</label>
                    <textarea id="completion-comment"></textarea>
                </div>
                <p class="completion-head control-label">Оценка работы</p>
                <div class="stars-rating big active-stars"><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span><span>&nbsp;</span></div>
                <input type="submit" class="button button--pop-up button--blue" value="Завершить">
            </form>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<section class="pop-up pop-up--act_response pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Добавление отклика к заданию</h4>
        <p class="pop-up-text">
            Вы собираетесь оставить свой отклик к этому заданию.
            Пожалуйста, укажите стоимость работы и добавьте комментарий, если необходимо.
        </p>
        <div class="addition-form pop-up--form regular-form">
            <form>
                <div class="form-group">
                    <label class="control-label" for="addition-comment">Ваш комментарий</label>
                    <textarea id="addition-comment"></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label" for="addition-price">Стоимость</label>
                    <input id="addition-price" type="text">
                </div>
                <input type="submit" class="button button--pop-up button--blue" value="Завершить">
            </form>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<div class="overlay"></div>