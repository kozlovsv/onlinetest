<?php

use kozlovsv\crud\helpers\ReturnUrl;


/* @var $this yii\web\View */
/* @var $model app\models\VocabularyWord */

$this->title = 'Создать словарное слово';
$this->params['breadcrumbs'][] = ['label' => 'Словарные слова', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vocabulary-word-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
