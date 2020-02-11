<?php

use app\models\User;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $model User */
/* @var $password string */
?>
<strong>Информация для входа в систему</strong>
<ul style="list-style: none;">
    <li>Ваш логин: <?= $model->email ?></li>
    <li>Ваш пароль: <?= $password ?></li>
    <li><?= Html::a('Ссылка в систему', Url::home('http')) ?></li>
</ul>