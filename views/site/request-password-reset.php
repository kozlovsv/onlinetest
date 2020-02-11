<?php

use app\models\form\PasswordResetRequestForm;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $form ActiveForm */
/* @var $model PasswordResetRequestForm */

$this->title = 'Восстановление доступа к системе';
?>
<div class="site-login">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3">
                <h1><?= Html::encode($this->title) ?></h1>
                <br>
                <p>Для восстановления доступа, пожалуйста, укажите логин, который Вы используете для входа. Вам на электронную почту будет выслано сообщение с инструкцией для дальнейших действий. Если к вашей учетной записи не привязана почта, пожалуйста, свяжитесь с администратором вашей школы.</p>
                <br>
                <?php $form = ActiveForm::begin([
                    'id' => 'request-password-reset-form',
                    'layout' => 'default',
                ]); ?>
                <?= $form->field($model, 'login', ['template' => "{input}\n{hint}\n{error}"])->textInput(['autofocus' => true, 'placeholder' => 'Логин']) ?>
                <div class="form-group" style="margin-top:  10px">
                    <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Вход', ['/login'], ['class' => 'btn btn-default']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>