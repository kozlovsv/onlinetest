<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model LoginForm */

use app\models\form\LoginForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Словарные слова | Вход в систему';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <div class="container">
        <div class="omb_login">
            <h1 class="omb_authTitle">Вход или <?= Html::a('Регистрация', ['site/registration']) ?></h1>
            <div class="row omb_row-sm-offset-3 omb_socialButtons">
                <div class="col-xs-12 col-sm-6">
                    <a href="<?=Url::to(['/site/auth', 'authclient' => 'google']);?>" class="btn btn-lg btn-block omb_btn-google">
                        <span>Google</span>
                    </a>
                </div>
            </div>

            <div class="row omb_row-sm-offset-3 omb_loginOr">
                <div class="col-xs-12 col-sm-6">
                    <hr class="omb_hrOr">
                    <span class="omb_spanOr">или</span>
                </div>
            </div>
            <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'form-omb_loginForm']]); ?>
                <div class="row omb_row-sm-offset-3">
                    <div class="col-xs-12 col-sm-6">
                        <?= $form->field($model, 'login', ['template' => "{input}\n{hint}\n{error}"])->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('login')]) ?>
                        <?= $form->field($model, 'password', ['template' => "{input}\n{hint}\n{error}"])->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>
                        <?= Html::submitButton('Войти', ['class' => 'btn btn-lg btn-primary btn-block', 'name' => 'login-button']) ?>
                    </div>
                </div>
                <div class="row omb_row-sm-offset-3">
                    <div class="col-xs-12 col-sm-3">
                        <?= $form->field($model, 'rememberMe')->checkbox() ?>
                    </div>
                    <div class="col-xs-12 col-sm-3">
                        <p class="omb_forgotPwd">
                            <?= Html::a('Забыли пароль?', ['site/request-password-reset']) ?>
                        </p>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>