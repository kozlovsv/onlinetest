<?php


use app\models\form\TrainingForm;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\widgets\ActiveForm;
use kozlovsv\crud\widgets\FormBuilder;
use kozlovsv\crud\widgets\ToolBarPanelContainer;

/* @var $this yii\web\View */
/* @var $model TrainingForm */


$this->title = 'Обучение слову ' . $model->testTaskQuestion->vocabularyWord->title;

$form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);

echo Html::tag('h1', 'Запомните слово', ['class' => 'form-header', 'style' => 'font-size: 25px']);
echo Html::tag('h1', Html::icon('ok') . '  ' . Html::encode($model->testTaskQuestion->vocabularyWord->title), ['style' => 'font-size: 25px; color: #5cb85c']);
echo ToolBarPanelContainer::widget([
        'buttonsRight' => [
            CrudButton::cancelButton('Закончить', ['view', 'id' => $model->testTaskQuestion->test_task_id], ['class' => 'btn btn-warning btn-lg form-cancel']),
        ],
        'buttonsLeft' => [
            CrudButton::saveButton('Я запомнил!', ['class' => 'btn btn-success btn-lg'])
        ],
        'options' => ['class' => 'form-group', 'style' => 'margin: 20px 0 50px 0'],
    ]
);

echo FormBuilder::widget([
    'form' => $form,
    'model' => $model,
    'attributes' => [
        'choice' => [
            'type' => FormBuilder::INPUT_HIDDEN,
        ]
    ],
]);

ActiveForm::end();