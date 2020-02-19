<?php

use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $form app\models\form\ResetPasswordForm */

?>
<h1>Изменение пароля в системе</h1>
<strong>Информация для входа в систему</strong>
<ul style="list-style: none;">
    <li>Ваш логин: <?= $form->user->login ?></li>
    <li>Ваш пароль: <?= $form->password ?></li>
    <li><?= Html::a('Ссылка в систему', Url::home(true)) ?></li>
</ul>