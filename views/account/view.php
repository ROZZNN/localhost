<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Book $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="book-view container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white">
                    <h1 class="h3 mb-0"><?= Html::encode($this->title) ?></h1>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-4">
                        <?= Html::a('<i class="fas fa-edit"></i> Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary me-2']) ?>
                        <?= Html::a('<i class="fas fa-trash"></i> Удалить', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить эту книгу?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </div>

                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped table-bordered', 'style' => 'color: white !important;'],
                        'attributes' => [
                            [
                                'attribute' => 'id',
                                'label' => 'ID',
                                'contentOptions' => ['class' => 'fw-bold', 'style' => 'color: white !important;']
                            ],
                            [
                                'attribute' => 'title',
                                'label' => 'Название',
                                'format' => 'ntext',
                                'contentOptions' => ['style' => 'color: white !important;']
                            ],
                            [
                                'attribute' => 'author',
                                'label' => 'Автор',
                                'format' => 'ntext',
                                'contentOptions' => ['style' => 'color: white !important;']
                            ],
                            [
                                'attribute' => 'visible',
                                'label' => 'Видимость',
                                'format' => 'boolean',
                                'contentOptions' => ['class' => 'text-center', 'style' => 'color: white !important;']
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
