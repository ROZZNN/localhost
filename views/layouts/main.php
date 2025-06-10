<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/iconfeb.webp')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
        body {
            background-color: rgba(0, 39, 27, 1);
            color: whitesmoke;
            padding-top: 60px; /* Отступ для фиксированной навигации */
        }
        footer {
            background-color: rgba(0, 39, 27, 1);
            color: whitesmoke;
            margin-top: 2rem;
        }
        .navbar {
            background-color: rgba(0, 118, 81, 1);
            color: whitesmoke;
        }
        .btn {
            background-color: cadetblue;
            border-color: cadetblue;
        }
        .container {
            max-width: 100%;
            padding: 0 15px;
        }
        @media (min-width: 768px) {
            .container {
                max-width: 720px;
            }
        }
        @media (min-width: 992px) {
            .container {
                max-width: 960px;
            }
        }
        @media (min-width: 1200px) {
            .container {
                max-width: 1140px;
            }
        }
        /* Стили для мобильной навигации */
        .navbar-toggler {
            display: none !important; /* Скрываем кнопку на всех устройствах */
        }
        .navbar-collapse {
            display: none !important; /* Скрываем выпадающее меню на всех устройствах */
        }
        @media (min-width: 768px) {
            .navbar-collapse {
                display: flex !important; /* Показываем меню на десктопе */
            }
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.75)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        /* Стили для форм */
        .form-control {
            background-color: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(0, 118, 81, 0.5);
        }
        .form-control:focus {
            background-color: #fff;
            border-color: cadetblue;
            box-shadow: 0 0 0 0.25rem rgba(95, 158, 160, 0.25);
        }
        /* Стили для таблиц */
        .table {
            color: whitesmoke;
        }
        .table-responsive {
            margin-bottom: 1rem;
        }
        /* Стили для карточек */
        .card {
            background-color: rgba(0, 118, 81, 0.1);
            border: 1px solid rgba(0, 118, 81, 0.2);
            margin-bottom: 1rem;
        }
        .card-header {
            background-color: rgba(0, 118, 81, 0.2);
            border-bottom: 1px solid rgba(0, 118, 81, 0.2);
        }
        /* Стили для пагинации */
        .pagination {
            justify-content: center;
            margin: 1rem 0;
        }
        .page-link {
            background-color: rgba(0, 118, 81, 0.2);
            border-color: rgba(0, 118, 81, 0.2);
            color: whitesmoke;
        }
        .page-link:hover {
            background-color: cadetblue;
            border-color: cadetblue;
            color: whitesmoke;
        }
        /* Стили для алертов */
        .alert {
            margin-bottom: 1rem;
        }
        /* Стили для хлебных крошек */
        .breadcrumb {
            background-color: rgba(0, 118, 81, 0.1);
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
        }
        .breadcrumb-item a {
            color: cadetblue;
        }
        .breadcrumb-item.active {
            color: whitesmoke;
        }
        /* Стили для мобильной навигации */
        .mobile-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 39, 27, 1);
            z-index: 1000;
            padding: 0.5rem 0;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        .mobile-nav .btn {
            color: whitesmoke;
            background: none;
            border: none;
            padding: 0.5rem;
            font-size: 0.8rem;
        }

        .mobile-nav .btn i {
            font-size: 1.2rem;
            display: block;
            margin-bottom: 0.2rem;
        }

        .mobile-nav .btn:hover {
            background-color: rgba(0, 118, 81, 0.5);
        }

        /* Отступ для мобильной навигации */
        @media (max-width: 768px) {
            body {
                padding-bottom: 70px;
            }
        }
    </style>
    <meta property="og:title" content="BookNetwork"/>
    <meta property="og:type" content="article" />
    <meta property="og:url" content="https://user13.teststand.ru/web/media/5019f7b9c1b5c635d2f40c845961f011.jpg" />
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
    m[i].l=1*new Date();
    for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
    k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(100873403, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true
    });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/100873403" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
</head>
<body class="d-flex flex-column h-100">
    <script type='application'>
        {
        "@context": "https://schema.org",
        "@type": "Software",
        "name": "BookNetwork",
        "description": "Ваша книга в вашем телефоне!",
        "applicationCategory": "EducationalApplication"
        }
    </script>
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => 'BookNetwork',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark navbar fixed-top'],
        'collapseOptions' => ['class' => 'justify-content-end']
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => 'Главная', 'url' => ['/site/index']],
            ['label' => 'Публичные книги', 'url' => ['/site/search']],
            Yii::$app->user->isGuest
            ? ['label' => 'Регистрация', 'url' => ['/site/registration']]
            : '',
            !Yii::$app->user->isGuest && Yii::$app->user->identity->id_role === 'administrator'
            ? ['label' => 'Панель администратора', 'url' => ['/admin']]
            : '',
            !Yii::$app->user->isGuest
            ? ['label' => 'Аккаунт', 'items' => [
                ['label' => 'Мой профиль', 'url' => ['/account']],
            ]]
            : '',
            Yii::$app->user->isGuest
                ? ['label' => 'Войти', 'url' => ['/site/login']]
                : '<li class="nav-item">'
                    . Html::beginForm(['/site/logout'])
                    . Html::submitButton(
                        'Выйти (' . Yii::$app->user->identity->login . ')',
                        ['class' => 'nav-link btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'
        ]
    ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3">
    <div class="container">
        <div class="row text-muted">
            <div class="col-12 text-center">&copy; BookNetwork <?= date('Y') ?></div>
        </div>
    </div>
</footer>

<?php if (Yii::$app->controller->action->id !== 'about'): ?>
<!-- Мобильная навигация -->
<div class="mobile-nav d-md-none">
    <div class="row m-0">
        <div class="col-3 text-center">
            <a href="<?= Yii::getAlias('@web') ?>/site/index" class="btn">
                <i class="fas fa-home"></i>
                <div>Главная</div>
            </a>
        </div>
        <div class="col-3 text-center">
            <a href="<?= Yii::getAlias('@web') ?>/site/about" class="btn">
                <i class="fas fa-book"></i>
                <div>Читалка</div>
            </a>
        </div>
        <div class="col-3 text-center">
            <a href="<?= Yii::getAlias('@web') ?>/site/search" class="btn">
                <i class="fas fa-search"></i>
                <div>Поиск</div>
            </a>
        </div>
        <div class="col-3 text-center">
            <a href="<?= Yii::getAlias('@web') ?>/account" class="btn">
                <i class="fas fa-user"></i>
                <div>Профиль</div>
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
