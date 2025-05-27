<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var ActiveForm $form */
$this->title = 'Sign up';
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
        <?= $form->field($model, 'login')->textInput(['autofocus' => true, 'required' => true, 'placeholder' => 'Enter your login'])->hint('Login must be between 3 and 20 characters') ?>
        <?= $form->field($model, 'email')->input('email', ['required' => true, 'placeholder' => 'Enter your email'])->hint('Please enter a valid email address') ?>
        <?= $form->field($model, 'password')->passwordInput(['required' => true, 'placeholder' => 'Enter your password'])->hint('Password must be at least 8 characters') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Sign up', ['class' => 'btn btn-primary']) ?>
        </div>
        <p>39ke35r7</p>
    <?php ActiveForm::end(); ?>

</div><!-- site-registration -->
