<?php


use app\models\TestTaskQuestion;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\widgets\ToolBarPanelContainer;

/* @var $this yii\web\View */
/* @var $model TestTaskQuestion */


$this->title = 'Вы неверно ответили';

echo Html::tag('h1', 'Правильный ответ', ['class' => 'form-header', 'style' => 'font-size: 25px']);
echo Html::tag('h1', Html::icon('ok') . '  ' . Html::encode($model->vocabularyWord->title), ['style' => 'font-size: 25px; color: #5cb85c']);
echo Html::tag('h1', 'Ваш ответ', ['class' => 'form-header', 'style' => 'font-size: 25px']);
echo Html::tag('h1', Html::icon('remove') . '  ' . Html::encode($model->answer), ['style' => 'font-size: 25px; color: #d9534f']);
echo ToolBarPanelContainer::widget([
        'buttonsRight' => [
            CrudButton::cancelButton('Закончить тест', ['view', 'id' => $model->test_task_id], ['class' => 'btn btn-warning btn-lg form-cancel']),
        ],
        'buttonsLeft' => [
            Html::a('Я выучил!', ['next', 'id' => $model->test_task_id], ['class' => 'btn btn-success btn-lg'])
        ],
        'options' => ['class' => 'form-group', 'style' => 'margin: 20px 0 50px 0'],
    ]
);


