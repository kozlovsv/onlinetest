<?php

use app\models\TestTask;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\widgets\GridView;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\Progress;
use yii\data\ArrayDataProvider;
use kozlovsv\crud\helpers\ReturnUrl;


/* @var $this yii\web\View */
/* @var $model TestTask */

$this->title = "Тест № {$model->id}";
$this->params['breadcrumbs'][] = ['label' => 'Мои тесты', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = $this->title;

$isModal = false;

/**
 * @param TestTask $model
 * @return string
 */
function getGradeColor($model)
{
    switch ($model->getGrade()) {
        case 5:
            return 'success';
            break;
        case 4:
            return 'info';
            break;
        case 3:
            return 'warning';
            break;
        default:
            return 'danger';
    }
}

$gradeClass = getGradeColor($model);
$statusClass = ($model->status == TestTask::STATUS_NEW) ? 'warning' : 'success';
$trainingStatusClass = ($model->training_status == TestTask::STATUS_NEW) ? 'warning' : 'success';
$questionsStayCountClass = $model->getQuestionsTestStayCount() <> 0 ? 'danger' : '';

/**
 * @param TestTask $model
 * @return string
 */

function getButtonTest($model){
    if (!$model->canTest()) return '';
    if ($model->getQuestionsPassedTestCount() == 0) return Html::a('Тест', ['test', 'id' => $model->id], ['class' => 'btn btn-info']);

    return ButtonDropdown::widget([
        'label' => 'Тест',
        'options' => ['class' => 'btn-info'],
        'dropdown' => [
            'items' => [
                ['label' => 'Продолжить', 'url' => ['test', 'id' => $model->id], 'visible' => $model->canTestContinue()],
                ['label' => 'Пройти заново', 'url' => ['re-test', 'id' => $model->id], 'linkOptions' => ['data' => ['confirm' => 'Пройти тест заново? Все ранее веденные ответы пропадут.']]],
            ],
        ],
    ]);
}

/**
 * @param TestTask $model
 * @return string
 */
function getButtonTraining($model){
    if (!$model->canTest()) return '';
    if ($model->getQuestionsPassedTrainingCount() == 0) return Html::a('Обучение', ['training', 'id' => $model->id], ['class' => 'btn btn-success']);

    return ButtonDropdown::widget([
        'label' => 'Обучение',
        'options' => ['class' => 'btn-success'],
        'dropdown' => [
            'items' => [
                ['label' => 'Продолжить', 'url' => ['training', 'id' => $model->id], 'visible' => $model->canTrainingContinue()],
                ['label' => 'Пройти заново', 'url' => ['re-training', 'id' => $model->id], 'linkOptions' => ['data' => ['confirm' => 'Пройти обучение заново?']]],
            ],
        ],
    ]);
}

/**
 * @param TestTask $model
 * @param $trainingStatusClass
 * @return array
 * @throws Exception
 */

function getTrainingStatusAttribute($model, $trainingStatusClass) {
    if ($model->training_status == TestTask::STATUS_FINISHED || $model->getQuestionsPassedTrainingCount() == 0) {
        return [
            'attribute' => 'trainingStatusLabel',
            'contentOptions' => ['class' => $trainingStatusClass],
            'captionOptions' => ['class' => $trainingStatusClass],
        ];
    }
    return [
        'attribute' => 'training_status',
        'format' => 'raw',
        'value' => Progress::widget([
            'label' => $model->getQuestionsPassedTrainingCount() . ' / ' . $model->getQuestionsCount(),
            'percent' => $model->passedTrainingPercent,
            'barOptions' => ['class' => 'progress-bar-success'],
        ])
    ];
}

/**
 * @param TestTask $model
 * @param $statusClass
 * @return array
 * @throws Exception
 */

function getTestStatusAttribute($model, $statusClass) {
    if ($model->status == TestTask::STATUS_FINISHED || $model->getQuestionsPassedTestCount() == 0) {
       return  [
            'attribute' => 'statusLabel',
            'contentOptions' => ['class' => $statusClass],
            'captionOptions' => ['class' => $statusClass],
        ];
    }
    return [
        'attribute' => 'status',
        'format' => 'raw',
        'value' => Progress::widget([
            'label' => $model->getQuestionsPassedTestCount() . ' / ' . $model->getQuestionsCount(),
            'percent' => $model->passedTestPercent,
            'barOptions' => ['class' => 'progress-bar-success'],
        ])
    ];
}

?>
<div class="test-task-view">
    <h1><?= Html::encode("$this->title ") ?></h1>
    <?php
    echo ToolBarPanelContainer::widget(
        [
            'buttonsLeft' => [
                CrudButton::cancelButton(Html::icon('arrow-left')),
                getButtonTest($model),
                getButtonTraining($model),
            ],
            'buttonsRight' => [
                CrudButton::deleteButton($model::tableName(), $model->getPrimaryKey()),
            ],
            'options' => ['class' => 'form-group', 'style' => 'margin-bottom: 10px'],
        ]
    );
    ?>
    <div class="clearfix" style="margin-bottom: 10px"></div>
    <?php
    echo Html::tag('h1', Html::encode($model->user->name));
    /** @noinspection PhpUndefinedFieldInspection */
    echo yii\widgets\DetailView::widget(
        [
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'grade',
                    'visible' => $model->getQuestionsTestStayCount() == 0,
                    'contentOptions' => ['class' => $gradeClass],
                    'captionOptions' => ['class' => $gradeClass],
                ],
                [
                    'attribute' => 'rating',
                    'visible' => $model->getQuestionsTestStayCount() == 0,
                    'contentOptions' => ['class' => $gradeClass],
                    'captionOptions' => ['class' => $gradeClass],
                    'format' => 'raw',
                    'value' => Progress::widget([
                        'label' => $model->rating,
                        'percent' => $model->rating,
                        'barOptions' => ['class' => 'progress-bar-info'],
                    ])
                ],
                getTestStatusAttribute($model, $statusClass),
                getTrainingStatusAttribute($model, $trainingStatusClass),
            ],
        ]
    );
    /** @noinspection PhpUndefinedFieldInspection */
    echo yii\widgets\DetailView::widget(
        [
            'model' => $model,
            'attributes' => [
                'questionsCount',
                'uniqueLettersString',
                'created_at:datetime',
                'passed_at:datetime',
            ],
        ]
    );
    ?>
    <div class="clearfix" style="margin-bottom: 10px"></div>
    <?php
    if ($model->getQuestionsPassedTestCount() > 0) {
        echo Html::tag('h1', 'Ответы');
        echo GridView::widget(
            [
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $model->getTestTaskQuestions()->with('vocabularyWord')->andWhere(['<>', 'answer', ''])->orderBy('id')->all(),
                    'sort' => false,
                    'pagination' => false,
                ]),
                'layout' => '{items}',
                'actionColumnsBefore' => [],
                'actionColumnsAfter' => [],
                'columns' => [
                    'vocabularyWord.title',
                    'answer',
                    'resultLabel'
                ],
                'rowOptions' => function ($model) {
                    return $model->result ? ['class' => 'success'] : ['class' => 'danger'];
                },
            ]
        );
    }
    ?>

</div>