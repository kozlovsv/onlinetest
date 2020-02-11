<?php

use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use yii\helpers\Html;
use kozlovsv\crud\helpers\ReturnUrl;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = $this->title;

$isModal = true;
?>
<div class="user-view">
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
                'id',
                'login',
                'name',
                'email',
                'created_at',
            ],
        ]
    );
    ?>
</div>