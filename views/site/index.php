<?php

/** @var yii\web\View $this */
/** @var app\models\Post[] $posts */

use yii\helpers\Html;

$this->title = 'BookNetwork';
?>
    <div class="site-index">


    <!-- Основной контент -->
    <div class="container-fluid px-0">
        <div class="jumbotron text-center bg-transparent position-relative">
            <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div style="position: relative;">
                            <img src="<?= Yii::getAlias('@web') ?>/media/5019f7b9c1b5c635d2f40c845961f011.jpg" class="d-block w-100 vh-100" style="object-fit: cover;" alt="...">
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);"></div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div style="position: relative;">
                            <img src="<?= Yii::getAlias('@web') ?>/media/c03bb957b7ec2852040cec6abd07f331.jpg" class="d-block w-100 vh-100" style="object-fit: cover;" alt="...">
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);"></div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div style="position: relative;">
                            <img src="<?= Yii::getAlias('@web') ?>/media/iStock-1460007178-2048x1080.webp" class="d-block w-100 vh-100" style="object-fit: cover;" alt="...">
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="position-absolute top-50 start-50 translate-middle">
                <h1 class="text-white display-4" style="text-shadow: 12px 12px 12px rgba(0,0,0,1);">Откройте новые страницы своих книг!</h1>
            </div>
        </div>

        <div style="height: 25px; background-color: rgba(0, 118, 81, 1);"></div>

        <div class="container">
            <div class="text-white text-center mb-5" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                <p class="lead mb-4">
                    Добро пожаловать на сайт!
                </p>

                <h3 class="mb-4">Как это работает?</h3>
                <div class="row justify-content-center mb-5">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-2">✨ Выбирайте книгу: В нашей библиотеке вы найдёте широкий выбор произведений.</li>
                            <li class="mb-2">📈 Следите за прогрессом: Наше приложение автоматически отслеживает ваши успехи</li>
                            <li class="mb-2">📱 Загружайте свои книги и читайте их</li>
                        </ul>
                    </div>
                </div>
            </div>

            <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->id_role === 1): ?>
                <div class="text-center mb-4">
                    <?= Html::a('Создать пост', ['/admin/create'], ['class' => 'btn btn-success']) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($posts)): ?>
                <div class="row">
                    <?php foreach ($posts as $post): ?>
                        <div class="col-md-12 mb-4">
                            <div class="card shadow-sm" style="background-color: rgba(0, 39, 27, 1); color: white;">
                                <div class="card-body" style="border-color: white;">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; margin-right: 10px;">
                                                <?= strtoupper(substr($post->user->login, 0, 1)) ?>
                                            </div>
                                            <div>
                                                <h5 class="card-title mb-0 text-white"><?= Html::encode($post->user->login) ?></h5>
                                                <small class="text-light">
                                                    <?= Yii::$app->formatter->asDatetime($post->created_at, 'php:d.m.Y H:i') ?>
                                                </small>
                                            </div>
                                        </div>
                                        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->id_role === 1): ?>
                                            <div class="dropdown">
                                                <button class="btn btn-link text-white" type="button" id="dropdownMenuButton<?= $post->id ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton<?= $post->id ?>">
                                                    <li><?= Html::a('<i class="fas fa-edit"></i> Редактировать', ['/admin/update', 'id' => $post->id], ['class' => 'dropdown-item']) ?></li>
                                                    <li><?= Html::a('<i class="fas fa-trash"></i> Удалить', ['/admin/delete', 'id' => $post->id], [
                                                        'class' => 'dropdown-item text-danger',
                                                        'data' => [
                                                            'confirm' => 'Вы уверены, что хотите удалить этот пост?',
                                                            'method' => 'post',
                                                        ],
                                                    ]) ?></li>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <p class="card-text" style="white-space: pre-wrap;"><?= Html::encode($post->massege) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <!-- Остальные секции остаются без изменений, но обернуты в container -->
        <div style="height: 25px; background-color: rgba(0, 118, 81, 1);"></div>
        
        <div class="container my-5">
        <h2 class="text-center mb-5" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">Отзывы наших пользователей</h2>
        <div class="row" style='color: white !important';>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Анна С.</h5>
                        <p class="card-text">
                            "Это приложение полностью изменило мой подход к изучению английского. Теперь я могу читать любимые книги и одновременно расширять свой словарный запас. Очень удобно!"
                        </p>
                        <div class="text-warning">
                            ★★★★★
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Михаил Д.</h5>
                        <p class="card-text">
                            "Великолепное приложение! Особенно нравится возможность загружать свои книги. За последние три месяца мой словарный запас значительно увеличился."
                        </p>
                        <div class="text-warning">
                            ★★★★★
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Елена П.</h5>
                        <p class="card-text">
                            "Очень удобный интерфейс и отличная подборка книг. Теперь чтение на английском стало намного проще и увлекательнее. Рекомендую всем!"
                        </p>
                        <div class="text-warning">
                            ★★★★★
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="body-content">
    </div>
</div>

<!-- Добавляем Font Awesome для иконок в мобильной навигации -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
