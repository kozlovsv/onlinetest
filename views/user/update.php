<?php

use kozlovsv\crud\helpers\ReturnUrl;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Изменить пользователя: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="user-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
