<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form app\models\form\RegistrationForm */
?>
<p>Здравствуйте, <?=Html::encode($form->name)?></p>
<p>Вы успешно зарегистрировались на портале <?=Html::encode(Yii::$app->params['brand'])?></p>
<p>Ваш логин для входа в систему: <?=Html::encode($form->login)?></p>
<p>Ваш пароль: <?=Html::encode($form->password)?></p>