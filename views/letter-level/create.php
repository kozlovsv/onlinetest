<?php

use kozlovsv\crud\helpers\ReturnUrl;


/* @var $this yii\web\View */
/* @var $model app\models\LetterLevel */

$this->title = 'Создать уровень для буквы';
$this->params['breadcrumbs'][] = ['label' => 'Уровни букв', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="letter-level-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
