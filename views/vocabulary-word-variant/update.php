<?php

use kozlovsv\crud\helpers\ReturnUrl;

/* @var $this yii\web\View */
/* @var $model app\models\VocabularyWordVariant */

$this->title = 'Изменить вариант неправильного написания: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Варианты неправильного написания', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="vocabulary-word-variant-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
