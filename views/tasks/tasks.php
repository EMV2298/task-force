<?php

/** @var yii\web\View $this */
/** @var app\models\Task $tasks */

use app\models\Categories;
use app\models\form\FilterTasks;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

$this->title = "Taskforce";

?>
<main class="main-content container">
    <div class="left-column">
        <h3 class="head-main head-task">Новые задания</h3>
        <?php
         echo ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => 'listViewTasks',
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