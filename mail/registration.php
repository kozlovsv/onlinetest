<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */
?>
<p>Здравствуйте, <?=Html::encode($user->name)?></p>
<p>Вы успешно зарегистрировались на портале <?=Html::encode(Yii::$app->params['brand'])?></p>
<p>Ваш логин для входа в систему: <?=Html::encode($user->login)?></p>
