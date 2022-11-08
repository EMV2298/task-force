<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\MainAsset;
use yii\helpers\Html;
use yii\helpers\Url;

MainAsset::register($this);
$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => '@web/favicon.ico']);

$id = \Yii::$app->user->getId();
$user = Yii::$app->user->getIdentity();

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <?=$this->head()?>
    <title><?=Html::encode($this->title)?></title>    
</head>
<body>
<?= $this->beginBody() ?>
<header class="page-header">
    <nav class="main-nav">
        <a href='#' class="header-logo">
            <img class="logo-image" src="/img/logotype.png" width=227 height=60 alt="taskforce">
        </a>
    <?php if (Yii::$app->request->url !== '/registration'): ?>
        <div class="nav-wrapper">
            <ul class="nav-list">
                <li class="list-item <?=$this->title === 'Новые' ? 'list-item--active' : '' ;?>">
                    <a href="<?= Yii::$app->urlManager->createUrl(['tasks']); ?>" class="link link--nav" >Новое</a>
                </li>
                <li class="list-item <?=$this->title === 'Мои' ? 'list-item--active' : '' ;?>">
                    <a href="<?= Yii::$app->urlManager->createUrl(['tasks/my/new']); ?>" class="link link--nav" >Мои задания</a>
                </li>
                <li class="list-item <?=$this->title === 'Добавить' ? 'list-item--active' : '' ;?>">
                    <a href="<?= Yii::$app->urlManager->createUrl(['tasks/add']); ?>" class="link link--nav" >Создать задание</a>
                </li>
                <li class="list-item <?=$this->title === 'Настройки' ? 'list-item--active' : '' ;?>">
                    <a href="<?= Yii::$app->urlManager->createUrl(['user/setting']); ?>" class="link link--nav" >Настройки</a>
                </li>
            </ul>
        </div>
    <?php endif; ?>
    </nav>
    <?php if (Yii::$app->request->url !== '/registration'): ?>
    <div class="user-block">
        <a href="#">
            <img class="user-photo" src="/uploads/user-avatar/<?=$user->avatar ? Html::encode($user->avatar) : '1.png' ; ?>" width="55" height="55" alt="Аватар">
        </a>
        <div class="user-menu">
            <p class="user-name"><?=Html::encode($user->name); ?></p>
            <div class="popup-head">
                <ul class="popup-menu">
                    <li class="menu-item">
                        <a href="<?= Yii::$app->urlManager->createUrl(['user/setting']); ?>" class="link">Настройки</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="link">Связаться с нами</a>
                    </li>
                    <li class="menu-item">
                        <a href="<?= Yii::$app->urlManager->createUrl('user/logout') ?>" class="link">Выход из системы</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>
</header>
<?= $content ?>
<?= $this->endBody() ?>
</body>
</html>

<?= $this->endPage() ?>
