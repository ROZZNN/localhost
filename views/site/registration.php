<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var ActiveForm $form */
$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration" style='width: 100%; max-width: 40%;'>
    <style>
        @media (max-width: 768px) {
            .registration {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 15px;
            }
        }
    </style>

    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'login')->textInput(['autofocus' => true, 'required' => true, 'placeholder' => 'Введите логин'])->hint('Логин должен содержать от 3 до 20 символов') ?>
        <?= $form->field($model, 'email')->input('email', ['required' => true, 'placeholder' => 'Введите email'])->hint('Пожалуйста, введите корректный email адрес') ?>
        <?= $form->field($model, 'password')->passwordInput(['required' => true, 'placeholder' => 'Введите пароль'])->hint('Пароль должен содержать минимум 8 символов') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary']) ?>
        </div>
        <p>39ke35r7</p>
    <?php ActiveForm::end(); ?>

</div><!-- site-registration -->
