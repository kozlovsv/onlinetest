<?php

use kozlovsv\crud\helpers\ReturnUrl;

/* @var $this yii\web\View */
/* @var $model app\models\LetterLevel */

$this->title = 'Изменить уровень буквы: ' . $model->letter->title;
$this->params['breadcrumbs'][] = ['label' => 'Уровни', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = ['label' => $model->letter->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="letter-level-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
