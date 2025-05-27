<?php
/** @var yii\web\View $this */
/** @var app\models\Book $model */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="book-card card">
    <div class="card-body">
        <h5 class="card-title"><?= Html::encode($model->title) ?></h5>
        <p class="card-text">
            <?php if ($model->description): ?>
                <?= Html::encode(mb_substr($model->description, 0, 150)) ?>
                <?= mb_strlen($model->description) > 150 ? '...' : '' ?>
            <?php else: ?>
                <em>Описание отсутствует</em>
            <?php endif; ?>
        </p>
    </div>
    <div class="card-footer">
        <?php if (Yii::$app->user->isGuest): ?>
            <?= Html::a('Войдите для чтения', ['/site/login'], ['class' => 'btn btn-primary']) ?>
        <?php else: ?>
            <?php if (!$model->isOwnedByUser(Yii::$app->user->id)): ?>
                <?= Html::a('Добавить в библиотеку', ['/site/add-to-library', 'id' => $model->id], [
                    'class' => 'btn btn-success',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите добавить эту книгу в свою библиотеку?',
                        'method' => 'post',
                    ],
                ]) ?>
            <?php else: ?>
                <?= Html::a('Читать', ['/site/read', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div> 