<?php


use app\models\form\ChooseAnswerForm;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\widgets\ToolBarPanelContainer;

/* @var $this yii\web\View */
/* @var $model ChooseAnswerForm */


$this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/buzz/1.2.0/buzz.min.js');
$this->registerJs('
    var mySound = new buzz.sound("/audio/fail.ogg");
    buzz.all().play();
');

$this->title = 'Вы неверно ответили';

echo Html::tag('h1', 'Правильный ответ', ['class' => 'form-header', 'style' => 'font-size: 25px']);
echo Html::tag('h1', Html::icon('ok') . '  ' . Html::encode($model->getTestTaskQuestion()->vocabularyWord->title), ['style' => 'font-size: 25px; color: #5cb85c']);
echo Html::tag('h1', 'Ваш ответ', ['class' => 'form-header', 'style' => 'font-size: 25px']);
echo Html::tag('h1', Html::icon('remove') . '  ' . Html::encode($model->choice), ['style' => 'font-size: 25px; color: #d9534f']);
echo ToolBarPanelContainer::widget([
        'buttonsLeft' => [
            Html::a('Я выучил!', ['test', 'id' => $model->getTestTaskQuestion()->test_task_id], ['class' => 'btn btn-success btn-lg'])
        ],
        'options' => ['class' => 'form-group', 'style' => 'margin: 20px 0 50px 0'],
    ]
);