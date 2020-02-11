<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model LoginForm */

use app\models\form\LoginForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Словарный диктант | Вход в систему';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-lg-offset-4">
                <h1>Вход в систему</h1>
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                    <?= $form->field($model, 'login', ['template' => "{input}\n{hint}\n{error}"])->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('login')]) ?>
                    <?= $form->field($model, 'password', ['template' => "{input}\n{hint}\n{error}"])->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>
                    <div class="form-group">
                        <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>
                    <?= Html::a('Забыли пароль?', ['site/request-password-reset']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>