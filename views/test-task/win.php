<?php

use app\models\LetterLevel;
use app\models\TestTask;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use yii\web\View;


/**
 * @var $this View
 * @var $model TestTask
 * @var $letterLevel LetterLevel
 * @var $level int
 */

$this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/buzz/1.2.0/buzz.min.js');
$this->registerJs('
    var mySound = new buzz.sound("/audio/win.ogg");
    buzz.all().play();
');

$this->title = "Тест на  букву {$model->letter->title} пройден!";
echo Html::tag('h1', $this->title, ['style' => 'font-size: 25px; color: #5cb85c; white-space: normal']);
echo Html::img('/images/dub-panda.svg', ['class' => 'img-responsive center-block' , 'style' => 'max-width: 80%']);
if ($level < $letterLevel->cnt_level) {
    $winText =  "Поздравляем! Вы перешли на <span style='color: #cd7900'>{$level}</span> уровень! ";
} else {
    $winText =  "Вы полностью прошли все слова на букву {$model->letter->title}!";
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