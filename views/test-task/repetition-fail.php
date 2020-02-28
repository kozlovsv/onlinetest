<?php

use app\models\TestTask;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use yii\web\View;


/**
 * @var $this View
 * @var $model TestTask
 */

$this->registerJsFile('//cdnjs.cloudflare.com/ajax/libs/buzz/1.2.0/buzz.min.js');
$this->registerJs('
    var mySound = new buzz.sound("/audio/fail.ogg");
    buzz.all().play();
');

$this->title = "Увы, но вы допустили слишком много ошибок!";
echo Html::tag('h1', $this->title, ['style' => 'font-size: 25px; color: #d9534f; white-space: normal']);
echo Html::img('/images/panda-want-eat.svg', ['class' => 'img-responsive center-block' , 'style' => 'max-width: 50%']);
echo Html::tag('h2', "Ваша оценка {$model->getGrade()}!", ['style' => 'font-size: 20px; color: #d9534f; white-space: normal']);
echo Html::tag('h2', 'Панда плачет. Она хочет кушать!', ['style' => 'font-size: 20px; color: #d9534f; white-space: normal']);

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