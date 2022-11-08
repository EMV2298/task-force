<?php

use taskforce\business\Task;
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = 'Мои';

?>
<main class="main-content container">
    <div class="left-menu">
        <h3 class="head-main head-task">Мои задания</h3>
        <ul class="side-menu-list">
            <?php if (!Yii::$app->user->getIdentity()->is_executor): ?>
            <li class="side-menu-item <?= $type === Task::STATUS_NEW ? 'side-menu-item--active' : '' ;?> ">
                <a href="<?= Yii::$app->urlManager->createUrl(['tasks/my', 'type' => Task::STATUS_NEW]); ?>" class="link link--nav">Новые</a>
            </li>
            <?php endif;?>
            <li class="side-menu-item <?= $type === Task::STATUS_IN_PROGRESS ? 'side-menu-item--active' : '' ;?>">
                <a href="<?= Yii::$app->urlManager->createUrl(['tasks/my/', 'type' => Task::STATUS_IN_PROGRESS]); ?>" class="link link--nav">В процессе</a>
            </li>
            <li class="side-menu-item <?= $type === Task::STATUS_DONE ? 'side-menu-item--active' : '' ;?>">
                <a href="<?= Yii::$app->urlManager->createUrl(['tasks/my/', 'type' => Task::STATUS_DONE]); ?>" class="link link--nav">Закрытые</a>
            </li>
        </ul>
    </div>
    <div class="left-column left-column--task">
        <h3 class="head-main head-regular"><?=$title;?></h3>
         <?php
         echo ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => 'listViewMyTasks',
            'summary' => '',
            'pager' => [
                'options' => ['class' => 'pagination-list'],
                'linkOptions' => ['class' => 'link link--page'],               
                'nextPageCssClass' => 'pagination-item mark',
                'prevPageCssClass' => 'pagination-item mark',
                'nextPageLabel' => '',
                'prevPageLabel' => '',     
                'pageCssClass' => 'pagination-item',
                'activePageCssClass' => 'pagination-item--active',
                'maxButtonCount' => 5,
            ]
        ]);
        ?>       
    </div>
</main>
