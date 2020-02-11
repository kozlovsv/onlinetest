<?php

use kozlovsv\crud\helpers\ReturnUrl;

/* @var $this yii\web\View */
/* @var $model app\models\VocabularyWord */

$this->title = 'Изменить словарное слово: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Словарные слова', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="vocabulary-word-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
