<?php

use app\widgets\ActionWidget;
use kartik\rating\StarRating;
use taskforce\business\Task;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
$userId = Yii::$app->user->getId();
$taskRules = new Task($task->customer_id, $task->executor_id, $task->status);
$action = $taskRules->getAvailableActions($userId);

$this->registerJsFile('https://api-maps.yandex.ru/2.1/?apikey=e666f398-c983-4bde-8f14-e3fec900592a&lang=ru_RU');
$this->registerJsFile('/js/map.js');


?>
<main class="main-content container">
    <div class="left-column">
        <div class="head-wrapper">
            <h3 class="head-main"><?= HTML::encode($task->title ?? ''); ?></h3>
            <p class="price price--big"><?= HTML::encode($task->budget ?? ''); ?>₽</p>
        </div>
        <p class="task-description"><?= HTML::encode($task->description ?? ''); ?></p>
        
        <?php if(!empty($action))
            {
            echo ActionWidget::widget(['action' => $action]);
            }?>
        <?php if ($task->lat && $task->long): ?>           
        <div class="task-map">
        <div class="map" id="map"></div>
        <input type="hidden" id="lat" value="<?= HTML::encode($task->lat); ?>">
        <input type="hidden" id="long" value="<?= HTML::encode($task->long); ?>">           
            <p class="map-address"><?= HTML::encode($task->address); ?></p>
        </div>
        <?php endif; ?>
        <?php 
            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => 'listViewOffer',
                'layout' =>  '<h4 class="head-regular">Отклики на задание</h4>{items}',
                'emptyText' => false,          
            ]);
        ?>
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
        <a href="<?= Yii::$app->urlManager->createUrl(['tasks/reject', 'task' => $task->id]) ?>"
            class="button button--pop-up button--orange">Отказаться</a>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<section class="pop-up pop-up--cancel pop-up--close">
    <div class="pop-up--wrapper">
        <h4>Отмена задания</h4>
        <p class="pop-up-text">
            <b>Внимание!</b><br>
            Вы собираетесь отменить поиск исполнителя для этого задания           
        </p>
        <a href="<?= Yii::$app->urlManager->createUrl(['tasks/cancel', 'task' => $task->id]) ?>" 
            class="button button--pop-up button--orange">Отказаться</a>
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
        <?php $form = ActiveForm::begin([
            'id' => 'offer',
            'action' => Yii::$app->urlManager->createUrl(['tasks/review']),          
            'fieldConfig' => [
              'template' => "{label}{input}",              
            ],
        ]);           
            echo $form->field($reviewModel, 'message')->textarea();?>
        
            <label class="control-label">Оценка работы</label>
            <div class="card-rate">

                <?php echo StarRating::widget([
                    'model' => $reviewModel, 
                    'attribute' => 'rating',
                    'pluginOptions' => [
                        'step' => '1',                               
                        'filledStar' => '<img src="/img/star-fill.svg"></img>',
                        'emptyStar' => '<img src="/img/star-empty.svg"></img>',                                      
                        'showClear' => false,
                        'showCaption' => false,
                    ], ]);?>
            </div>
            <?=$form->field($reviewModel, 'taskId', ['template' => '{input}', 'options' => ['tag' => false]])->hiddenInput(['value' => $task->id]);?>        
            <input type="submit" class="button button--pop-up button--blue" value="Завершить">
            <?php ActiveForm::end(); ?>
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
        <?php $form = ActiveForm::begin([
            'id' => 'offer',
            'action' => Yii::$app->urlManager->createUrl(['tasks/offer']),          
            'fieldConfig' => [
              'template' => "{label}{input}",              
            ],
        ]);           
            echo $form->field($offerModel, 'message')->textarea();           
            echo $form->field($offerModel, 'price');
            echo $form->field($offerModel, 'taskId', ['template' => '{input}', 'options' => ['tag' => false]])->hiddenInput(['value' => $task->id]);
        ?>        
            <input type="submit" class="button button--pop-up button--blue" value="Завершить">
            <?php ActiveForm::end(); ?>
        </div>
        <div class="button-container">
            <button class="button--close" type="button">Закрыть окно</button>
        </div>
    </div>
</section>
<div class="overlay"></div>