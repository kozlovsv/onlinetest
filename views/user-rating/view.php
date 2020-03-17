<?php

use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use kozlovsv\crud\helpers\ReturnUrl;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Рейтинг учеников', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = $this->title;

$isModal = true;
?>
<div class="user-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    echo ToolBarPanelContainer::widget(
        [
            'buttonsLeft' => [
                CrudButton::cancelButton('Закрыть'),
            ],
            'buttonsRight' => [
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
                'userAchievementsCount:text:Количество корон',
                'testTaskRepetitionCount:text:Количество контрольных',
                'averageRating:money:Средний рейтинг',
                'averageGrade:text:Средняя оценка',
            ],
        ]
    );
    ?>
</div>