<?php
use yii\helpers\Html;

/* @var $this yii\web\View */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<p>Ваша ссылка для смены пароля:</p>
<p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>