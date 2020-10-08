<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;


/** @noinspection PhpPossiblePolymorphicInvocationInspection */
$this->context->layout = 'empty';
$this->title = $name;
?>
<div class="site-error">
    <div class="container text-center">
        <h1><?= Html::encode($this->title) ?></h1>
        <p><?= Html::encode($message) ?></p>
    </div>
</div>
