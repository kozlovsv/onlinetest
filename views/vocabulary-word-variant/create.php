<?php

use kozlovsv\crud\helpers\ReturnUrl;


/* @var $this yii\web\View */
/* @var $model app\models\VocabularyWordVariant */

$this->title = 'Создать вариант неправильного написания';
$this->params['breadcrumbs'][] = ['label' => 'Варианты неправильного написания', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vocabulary-word-variant-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
