<?php

use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use yii\helpers\Html;
use kozlovsv\crud\helpers\ReturnUrl;


/* @var $this yii\web\View */
/* @var $model app\models\TestTask */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Пройденные тесты', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = $this->title;

$isModal = false;
?>
<div class="test-task-view">
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
                'user_id',
                'status',
                'created_at',
            ],
        ]
    );
    ?>
</div>