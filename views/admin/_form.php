<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

/** @var yii\web\View $this */
/** @var app\models\Book $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'visible')->checkbox() ?>

    <?= $form->field($model, 'uploadedFile')->fileInput() ?>

    <?= $form->field($model, 'pathway')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
// Создаем директорию для хранения книг, если она не существует
$storagePath = Yii::getAlias('@webroot/booksstorage');
if (!file_exists($storagePath)) {
    mkdir($storagePath, 0777, true);
}

// Генерируем уникальный ID для книги
$bookId = uniqid('book_');

// Формируем путь к файлу
$model->pathway = "/booksstorage/{$bookId}.txt";
?>
