<?php

use app\models\TestTaskQuestion;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\widgets\Pjax;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use yii\bootstrap\Progress;
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $question TestTaskQuestion
 * @var $offset int
 * @var $questionCount int
 */

$this->title = 'Обучение слову ' . $question->vocabularyWord->title;

$numWord = $offset + 1;
Pjax::begin(['enablePushState' => false]);
echo Html::tag('h2', Html::encode("Слово {$numWord} из {$questionCount}"));
echo Progress::widget([
    'percent' => intval(($numWord / $questionCount) * 100),
    'barOptions' => ['class' => 'progress-bar-info'],
    'options' => ['style' => 'margin-bottom: 40px'],
]);
echo Html::tag('h1', Html::icon('ok') . '  ' . Html::encode($question->vocabularyWord->title), ['class' => 'form-header', 'style' => 'font-size: 30px; color: #5cb85c']);
echo ToolBarPanelContainer::widget([
        'buttonsRight' => [
            CrudButton::cancelButton('Закрыть', ['view', 'id' => $question->test_task_id], ['class' => 'btn btn-warning btn-lg form-cancel']),
        ],
        'buttonsLeft' => [
            Html::a(Html::icon('arrow-left'), Url::to(['/test-task/training-test', 'id' => $question->test_task_id, 'offset' => $offset - 1]), ['class' => 'btn btn-info btn-lg' . ($offset > 0 ? '' : ' disabled')]),
            Html::a(Html::icon('arrow-right'), Url::to(['/test-task/training-test', 'id' => $question->test_task_id, 'offset' => $offset + 1]), ['class' => 'btn btn-success btn-lg']),
        ],
        'options' => ['class' => 'form-group', 'style' => 'margin: 40px 0 80px 0'],
    ]
);
Pjax::end();