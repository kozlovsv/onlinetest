<?php

use app\models\TestTask;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use yii\web\View;


/**
 * @var $this View
 * @var $model TestTask
 * @var $pandaLevel int
 */

$this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/buzz/1.2.0/buzz.min.js');
$this->registerJs('
    var mySound = new buzz.sound("/audio/win.ogg");
    buzz.all().play();
');

$this->title = "Вы покормили панду!";
echo Html::tag('h1', $this->title, ['style' => 'font-size: 25px; color: #5cb85c; white-space: normal']);
echo Html::img('/images/panda-eat2.svg', ['class' => 'img-responsive center-block' , 'style' => 'max-width: 50%']);
if ($pandaLevel < TestTask::CNT_PANDA_IS_FULL) {
    $cn = TestTask::CNT_PANDA_IS_FULL - $pandaLevel;
    $winText =  "Поздравляем Вы покормили панду! Осталось покормить <span style='color: #cd7900'>{$cn}</span>";
} else {
    $winText =  "Панда на сегодня сыта. Панда говорит спасибо!";
}
echo Html::tag('h2', $winText, ['style' => 'font-size: 20px; color: #5cb85c; white-space: normal']);

echo ToolBarPanelContainer::widget([
        'buttonsLeft' => [
            Html::a('Далее', ['/'], ['class' => 'btn btn-success btn-lg'])
        ],
        'buttonsRight' => [
            Html::a('Посмотреть', ['view', 'id' => $model->id], ['class' => 'btn btn-info btn-lg'])
        ],
        'options' => ['class' => 'form-group', 'style' => 'margin: 20px 0 50px 0'],
    ]
);