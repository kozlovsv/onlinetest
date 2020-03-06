<?php


use app\models\form\ChooseAnswerForm;
use app\models\TestTask;
use app\widgets\FormBuilder;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\widgets\ActiveForm;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use yii\bootstrap\Progress;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model ChooseAnswerForm */
/* @var $testTask TestTask */

$this->title = 'Как пишется правильно';

$this->registerJs("
    var mySound = new buzz.sound('/audio/words/{$model->getTestTaskQuestion()->vocabulary_word_id}.ogg');
    buzz.all().play();
    $('#btn-sound').click(function() {
        buzz.all().play();
    });
");

$form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);
echo Html::tag('h1', Html::a(Html::icon('volume-up'), null, ['class' => ['btn', 'btn-success', 'btn-lg'], 'id' => 'btn-sound']) . ' ' . Html::encode($this->title), ['class' => 'form-header', 'style' => 'font-size: 25px']);
echo Html::tag('h2', Html::encode("Вопрос {$testTask->getCurrentTestNumQuestion()} из {$testTask->getQuestionsCount()}"));
echo Progress::widget([
    'percent' => $testTask->getPassingTestPercent(),
    'barOptions' => ['class' => 'progress-bar-info'],
]);
echo FormBuilder::widget([
    'rowOptions' => ['class' => 'lead'],
    'form' => $form,
    'model' => $model,
    'attributes' => [
        'choice' => [
            'type' => FormBuilder::INPUT_TEXT,
            'options' => ['class' => [], 'spellcheck' => 'false', 'autocomplete' => 'off']
        ],
        'test_task_question_id' => [
            'type' => FormBuilder::INPUT_HIDDEN
        ]
    ]
]);

echo ToolBarPanelContainer::widget([
        'buttonsRight' => [
            CrudButton::cancelButton('Закончить тест', $testTask->is_repetition ?  ['view', 'id' => $testTask->id] : Url::home(), $options = ['class' => 'btn btn-warning btn-lg form-cancel']),
        ],
        'buttonsLeft' => [
            CrudButton::saveButton('Ответить', $options = ['class' => 'btn btn-success btn-lg']),
        ],
        'options' => ['class' => 'form-group', 'style' => 'margin: 20px 0 50px 0'],
    ]
);
ActiveForm::end();