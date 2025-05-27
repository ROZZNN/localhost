<?php

use app\models\Book;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\bootstrap5\Alert;
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Мои книги';
$this->params['breadcrumbs'][] = $this->title;

// Регистрируем CSS стили
$this->registerCss("
    .book-index {
        background-color: rgba(0, 39, 27, 1);
        color: white;
        padding: 20px;
        border-radius: 10px;
    }
    
    .user-profile {
        background-color: rgba(0, 118, 81, 1);
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    
    .book-item {
        background-color: rgba(0, 118, 81, 1);
        border: none !important;
        margin-bottom: 15px;
        border-radius: 8px;
        transition: transform 0.2s;
    }
    
    .book-item:hover {
        transform: translateY(-2px);
    }
    
    .btn-success {
        background-color: rgba(0, 118, 81, 1);
        border-color: rgba(0, 118, 81, 1);
    }
    
    .btn-success:hover {
        background-color: rgba(0, 39, 27, 1);
        border-color: rgba(0, 39, 27, 1);
    }
    
    .btn-primary {
        background-color: rgba(0, 118, 81, 1);
        border-color: rgba(0, 118, 81, 1);
    }
    
    .btn-primary:hover {
        background-color: rgba(0, 39, 27, 1);
        border-color: rgba(0, 39, 27, 1);
    }
    
    .alert-success {
        background-color: rgba(0, 118, 81, 0.2);
        border-color: rgba(0, 118, 81, 1);
        color: white;
    }
    
    .text-muted {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    
    h1, h2, h3, h4 {
        color: white;
    }
    
    .btn-outline-primary {
        color: white;
        border-color: white;
    }
    
    .btn-outline-primary:hover {
        background-color: rgba(0, 118, 81, 1);
        border-color: rgba(0, 118, 81, 1);
    }
    
    .btn-outline-danger {
        color: white;
        border-color: white;
    }
    
    .btn-outline-danger:hover {
        background-color: #dc3545;
        border-color: #dc3545;
    }
");
?>

<div class="book-index">
    <div class="user-profile mb-4">
        <div class="d-flex align-items-center">
            <div class="avatar-circle me-3">
                <img src="<?= Yii::$app->user->identity->id_avatar ? '/web/avatar/' . Yii::$app->user->identity->id_avatar . '.png' : '/web/avatar/1.png' ?>" class="rounded-circle" width="200" height="200" alt="Avatar">
            </div>
            <h2 style="font-size: 2em;"><?= Html::encode(Yii::$app->user->identity->login) ?></h2>
        </div>
    </div>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <?= Alert::widget([
            'options' => ['class' => 'alert-success'],
            'body' => Yii::$app->session->getFlash('success'),
        ]) ?>
    <?php endif; ?>

    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h3>Мои книги</h3>
        <div>
            <?php if (Yii::$app->user->identity->isAdmin()): ?>
                <?= Html::a('Добавить пост', ['/post/create'], ['class' => 'btn btn-primary me-2']) ?>
            <?php endif; ?>
            <?= Html::a('Загрузить книгу', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php if (Yii::$app->user->identity->isAdmin()): ?>
        <p>
            <?= Html::a('Добавить пост', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>

    <?php Pjax::begin(); ?>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => [
            'class' => 'book-item mb-3 p-3',
        ],
        'itemView' => function ($model, $key, $index, $widget) {
            return '<div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4>' . Html::encode($model->title) . '</h4>
                            <p class="text-muted mb-0">Автор: ' . Html::encode($model->author) . '</p>
                            <p class="text-muted mb-0">Статус: ' . ($model->visible ? 'Публичная' : 'Приватная') . '</p>
                        </div>
                        <div class="btn-group">
                            ' . Html::a('Читать', ['/site/read', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm me-2']) . '
                            ' . Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-outline-primary btn-sm me-2']) . '
                            ' . Html::a('Удалить', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-outline-danger btn-sm',
                                'data' => [
                                    'confirm' => 'Вы уверены, что хотите удалить эту книгу?',
                                    'method' => 'post',
                                ],
                            ]) . '
                        </div>
                    </div>';
        },
        'emptyText' => '<div class="alert alert-info">У вас пока нет загруженных книг.</div>',
        'summary' => '<div class="text-muted mb-3">Показано {begin}-{end} из {totalCount} книг</div>',
    ]) ?>
    <?php Pjax::end(); ?>

    <div class="site-index">
    <!-- Мобильная навигация -->
    <div class="d-md-none fixed-bottom bg-dark" style="z-index: 1000;">
        <div class="row m-0">
            <div class="col-3 p-0">
                <a href="/site/index" class="btn w-100 rounded-0 py-3" style="background-color:rgba(0, 118, 81, 1); color:whitesmoke;">
                    <i class="fas fa-home"></i>
                    <div class="small">Главная</div>
                </a>
            </div>
            <div class="col-3 p-0">
                <a href="/site/about" class="btn w-100 rounded-0 py-3" style="background-color:rgba(0, 118, 81, 1); color:whitesmoke;">
                    <i class="fas fa-book"></i>
                    <div class="small">читалка</div>
                </a>
            </div>
            <div class="col-3 p-0">
                <a href="#" class="btn w-100 rounded-0 py-3 disabled" style="background-color:rgba(0, 118, 81, 1); color:whitesmoke;">
                    <i class="fas fa-sign-in-alt"></i>
                    <div class="small">Вход</div>
                </a>
            </div>
            <div class="col-3 p-0">
                <a href="/account" class="btn w-100 rounded-0 py-3" style="background-color:rgba(0, 118, 81, 1); color:whitesmoke;">
                    <i class="fas fa-user"></i>
                    <div class="small">Профиль</div>
                </a>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</div>
