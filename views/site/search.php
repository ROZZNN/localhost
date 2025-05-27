<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\bootstrap5\Alert;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Поиск книг';
$this->params['breadcrumbs'][] = $this->title;

// Регистрируем CSS стили
$this->registerCss("
    .search-page {
        background-color: rgba(0, 39, 27, 1);
        color: white;
        padding: 20px;
        border-radius: 10px;
        min-height: 100vh;
    }
    
    .search-header {
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
        padding: 20px;
    }
    
    .book-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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
    
    .pagination {
        margin-top: 20px;
    }
    
    .pagination .page-item .page-link {
        background-color: rgba(0, 118, 81, 1);
        border-color: rgba(0, 118, 81, 1);
        color: white;
    }
    
    .pagination .page-item.active .page-link {
        background-color: rgba(0, 39, 27, 1);
        border-color: rgba(0, 39, 27, 1);
    }
    
    .pagination .page-item .page-link:hover {
        background-color: rgba(0, 39, 27, 1);
        border-color: rgba(0, 39, 27, 1);
    }
    
    .book-title {
        font-size: 1.5em;
        margin-bottom: 10px;
        color: white;
    }
    
    .book-author {
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 5px;
    }
    
    .book-description {
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 15px;
    }
    
    .book-status {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.9em;
        margin-bottom: 10px;
    }
    
    .book-status-public {
        background-color: rgba(0, 118, 81, 0.3);
        color: white;
    }
    
    .book-status-private {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
    }
");
?>

<div class="search-page">
    <div class="search-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <?= Alert::widget([
            'options' => ['class' => 'alert-success'],
            'body' => Yii::$app->session->getFlash('success'),
        ]) ?>
    <?php endif; ?>

    <?php Pjax::begin(); ?>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => [
            'class' => 'book-item',
        ],
        'itemView' => function ($model, $key, $index, $widget) {
            return '<div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="book-title">' . Html::encode($model->title) . '</h4>
                            <div class="book-author">Автор: ' . Html::encode($model->author) . '</div>
                            ' . ($model->description ? '<div class="book-description">' . Html::encode($model->description) . '</div>' : '') . '
                            <div class="book-status ' . ($model->visible ? 'book-status-public' : 'book-status-private') . '">
                                ' . ($model->visible ? 'Публичная' : 'Приватная') . '
                            </div>
                        </div>
                        <div class="btn-group">
                            ' . Html::a('Читать', ['/site/read', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm me-2']) . '
                            ' . (!Yii::$app->user->isGuest && !$model->isOwnedByUser(Yii::$app->user->id) ? 
                                Html::a('Добавить в библиотеку', ['/site/add-to-library', 'id' => $model->id], [
                                    'class' => 'btn btn-success btn-sm',
                                    'data' => [
                                        'confirm' => 'Добавить книгу в вашу библиотеку?',
                                        'method' => 'post',
                                    ],
                                ]) : '') . '
                        </div>
                    </div>';
        },
        'emptyText' => '<div class="alert alert-info">Книги не найдены.</div>',
        'summary' => '<div class="text-muted mb-3">Показано {begin}-{end} из {totalCount} книг</div>',
        'pager' => [
            'options' => ['class' => 'pagination justify-content-center'],
        ],
    ]) ?>
    <?php Pjax::end(); ?>
</div>

<style>
.book-card {
    height: 100%;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
}

.book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.book-card .card-body {
    display: flex;
    flex-direction: column;
}

.book-card .card-title {
    font-size: 1.2em;
    margin-bottom: 0.5em;
    color: #333;
}

.book-card .card-text {
    color: #666;
    flex-grow: 1;
}

.book-card .card-footer {
    background: none;
    border-top: 1px solid #eee;
    padding: 0.75rem;
}

.book-card .btn {
    width: 100%;
}

@media (max-width: 768px) {
    .book-card {
        margin-bottom: 1rem;
    }
}
</style> 