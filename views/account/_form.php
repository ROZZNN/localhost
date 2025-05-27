<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Book $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
        'enableClientValidation' => true,
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php if (Yii::$app->user->identity->isAdmin()): ?>
        <?= $form->field($model, 'visible')->dropDownList([
            '1' => 'Публичная',
            '0' => 'Приватная'
        ]) ?>
    <?php else: ?>
        <?= Html::activeHiddenInput($model, 'visible', ['value' => '0']) ?>
    <?php endif; ?>

    <?= $form->field($model, 'uploadedFile')->fileInput(['accept' => '.txt'])->hint('Загрузите текстовый файл книги (.txt)') ?>

    <?= $form->field($model, 'pathway')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
// Добавляем JavaScript для валидации формы
$this->registerJs("
    $('form').on('beforeSubmit', function(e) {
        var form = $(this);
        if (form.find('input[type=\"file\"]').val() === '') {
            alert('Пожалуйста, выберите файл книги');
            return false;
        }
        return true;
    });
");
?>
