<?php

/** @var yii\web\View $this */
/** @var app\models\Task $tasks */

use app\models\Categories;
use app\models\form\FilterTasks;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = "Taskforce";

?>
<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main head-task">Новые задания</h3>
        <?php foreach ($tasks as $task) : ?>
            <div class="task-card">
                <div class="header-task">
                    <a href="#" class="link link--block link--big"><?= HTML::encode($task->title) ?></a>
                    <p class="price price--task"><?= HTML::encode($task->budget ?? '') ?> ₽</p>
                </div>
                <p class="info-text"><?= Yii::$app->formatter->asRelativeTime(HTML::encode($task->dt_add)) ?></p>
                <p class="task-text"><?= HTML::encode($task->description ?? '') ?></p>
                <div class="footer-task">
                    <p class="info-text town-text"><?= HTML::encode($task->city->name ?? '') ?></p>
                    <p class="info-text category-text"><?= HTML::encode($task->category->name) ?></p>
                    <a href="#" class="button button--black">Смотреть Задание</a>
                </div>
            </div>
        <?php endforeach ?>
        <div class="pagination-wrapper">
            <ul class="pagination-list">
                <li class="pagination-item mark">
                    <a href="#" class="link link--page"></a>
                </li>
                <li class="pagination-item">
                    <a href="#" class="link link--page">1</a>
                </li>
                <li class="pagination-item pagination-item--active">
                    <a href="#" class="link link--page">2</a>
                </li>
                <li class="pagination-item">
                    <a href="#" class="link link--page">3</a>
                </li>
                <li class="pagination-item mark">
                    <a href="#" class="link link--page"></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="right-column">
        <div class="right-card black">
            <div class="search-form">
                <h4 class="head-card">Категории</h4>

                <?php $form = ActiveForm::begin([
                    'method' => 'get',
                    'id' => 'filterTasks',
                    'fieldConfig' => ['template' => '{input}']
                ]);

                echo $form->field($model, 'categories')
                    ->checkboxList(Categories::getCategories(), ['class' => 'checkbox-wrapper control-label']); ?>

                <h4 class="head-card">Дополнительно</h4>

                <?php
                echo $form->field($model, 'noOffer')->checkbox(['labelOptions' => ['class' => 'control-label',]]);
                echo $form->field($model, 'isDistant')->checkbox(['labelOptions' => ['class' => 'control-label',]]);
                ?>

                <h4 class="head-card">Период</h4>

                <?php echo $form->field($model, 'period')->dropDownList(FilterTasks::PERIODS); ?>

                <input type="submit" class="button button--blue" value="Искать">

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</main>