<?php

use app\models\form\ResetPasswordForm;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model ResetPasswordForm */

$this->title = 'Новый пароль';
?>
<div class="site-register">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-lg-offset-4">
                <h1><?= Html::encode($this->title) ?></h1>
                <p>Введите ваш новый пароль:</p>
                <?php $form = ActiveForm::begin([
                    'id' => 'reset-password-form',
                    'layout' => 'default',
                ]); ?>
                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
                <div class="form-group">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>