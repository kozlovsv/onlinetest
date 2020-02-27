<?php

use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use yii\helpers\Html;
use kozlovsv\crud\helpers\ReturnUrl;


/* @var $this yii\web\View */
/* @var $model app\models\LetterLevel */

$this->title = $model->letter->title;
$this->params['breadcrumbs'][] = ['label' => 'Уровни букв', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = $this->title;

$isModal = true;
?>
<div class="letter-level-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    echo ToolBarPanelContainer::widget(
        [
            'buttonsLeft' => [
                CrudButton::editButton($model::tableName(), $model->getPrimaryKey(), $isModal),
                CrudButton::cancelButton('Закрыть'),
            ],
            'buttonsRight' => [
                CrudButton::deleteButton($model::tableName(), $model->getPrimaryKey()),
            ],
            'options' => ['class' => 'form-group', 'style' => 'margin-bottom: 10px'],
        ]
    );
    ?>
    <div class="clearfix"></div>
    <?php
    echo yii\widgets\DetailView::widget(
        [
            'model' => $model,
            'attributes' => [
                'cnt_word_in_level',
                'cnt_level',
            ],
        ]
    );
    ?>
</div>