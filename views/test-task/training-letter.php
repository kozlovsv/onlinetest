<?php


use app\models\Letter;
use app\models\VocabularyWord;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use yii\bootstrap\Progress;
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $word VocabularyWord
 * @var $letter Letter
 * @var $offset int
 * @var $wordsCount int
 */

$this->title = 'Обучение слову ' . $word->title;

$numWord = $offset + 1;

echo Html::tag('h2', Html::encode("Слово {$numWord} из {$wordsCount}"));
echo Progress::widget([
    'percent' => intval(($numWord / $wordsCount) * 100),
    'barOptions' => ['class' => 'progress-bar-info'],
    'options' => ['style' => 'margin-bottom: 40px'],
]);
echo Html::tag('h1', Html::icon('ok') . '  ' . Html::encode($word->title), ['class' => 'form-header', 'style' => 'font-size: 30px; color: #5cb85c']);
echo ToolBarPanelContainer::widget([
        'buttonsRight' => [
            CrudButton::cancelButton('Закончить', [Url::home()], ['class' => 'btn btn-warning btn-lg form-cancel']),
        ],
        'buttonsLeft' => [
            Html::a('Назад', Url::to(['/test-task/training-letter', 'id' => $letter->id, 'offset' => $offset - 1]),['class' => 'btn btn-info btn-lg'. ($offset > 0 ? '' : ' disabled')]),
            Html::a('Дальше', Url::to(['/test-task/training-letter', 'id' => $letter->id, 'offset' => $offset + 1]), ['class' => 'btn btn-success btn-lg']),
        ],
        'options' => ['class' => 'form-group', 'style' => 'margin: 40px 0 80px 0'],
    ]
);