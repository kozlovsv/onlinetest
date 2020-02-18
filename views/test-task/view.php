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
$questionsStayCountClass = $model->getQuestionsStayCount() <> 0 ? 'danger' : '';

?>
<div class="test-task-view">
    <h1><?= Html::encode("$this->title ") ?></h1>
    <?php
    echo ToolBarPanelContainer::widget(
        [
            'buttonsLeft' => [
                CrudButton::cancelButton(Html::icon('arrow-left')),
                $model->canTest() ? ButtonDropdown::widget([
                    'label' => 'Тест',
                    'options' => ['class' => 'btn-info'],
                    'dropdown' => [
                        'items' => [
                            ['label' => 'Продолжить', 'url' => ['next', 'id' => $model->id], 'visible' => $model->canTestContinue()],
                            ['label' => 'Пройти заново', 'url' => ['repass', 'id' => $model->id], 'linkOptions' => ['data' => ['confirm' => 'Пройти тест заново? Все ранее веденные ответы пропадут.']]],
                        ],
                    ],
                ]) : '',
                $model->canTest() ? ButtonDropdown::widget([
                    'label' => 'Обучение',
                    'options' => ['class' => 'btn-success'],
                    'dropdown' => [
                        'items' => [
                            ['label' => 'Продолжить', 'url' => ['study', 'id' => $model->id], 'visible' => $model->canStudyContinue()],
                            ['label' => 'Пройти заново', 'url' => ['restudy', 'id' => $model->id], 'linkOptions' => ['data' => ['confirm' => 'Пройти обучение заново?']]],
                        ],
                    ],
                ]) : '',
            ],
            'buttonsRight' => [
                CrudButton::deleteButton($model::tableName(), $model->getPrimaryKey()),
            ],
            'options' => ['class' => 'form-group', 'style' => 'margin-bottom: 10px'],
        ]
    );
    ?>
    <div class="clearfix"></div>
    <?php
    /** @noinspection PhpUndefinedFieldInspection */
    echo yii\widgets\DetailView::widget(
        [
            'model' => $model,
            'attributes' => [
                'user.name',
                [
                    'attribute' => 'grade',
                    'visible' => $model->getQuestionsStayCount() == 0,
                    'contentOptions' => ['class' => $gradeClass],
                    'captionOptions' => ['class' => $gradeClass],
                ],
                [
                    'attribute' => 'rating',
                    'visible' => $model->getQuestionsStayCount() == 0,
                    'contentOptions' => ['class' => $gradeClass],
                    'captionOptions' => ['class' => $gradeClass],
                    'format' => 'raw',
                    'value' => Progress::widget([
                        'label' => $model->rating,
                        'percent' => $model->rating,
                        'barOptions' => ['class' => 'progress-bar-info'],
                    ])
                ],
                [
                    'attribute' => 'statusLabel',
                    'contentOptions' => ['class' => $statusClass],
                    'captionOptions' => ['class' => $statusClass],
                ],
                [
                    'attribute' => 'trainingStatusLabel',
                    'contentOptions' => ['class' => $trainingStatusClass],
                    'captionOptions' => ['class' => $trainingStatusClass],
                ],
                'created_at:datetime',
                'passed_at:datetime',
                'questionsCount',
                [
                    'attribute' => 'questionsStayCount',
                    'visible' => $model->getQuestionsStayCount() > 0,
                    'contentOptions' => ['class' => 'danger'],
                    'captionOptions' => ['class' => 'danger'],
                ],
                'uniqueLettersString',
            ],
        ]
    );
    ?>
    <div class="clearfix" style="margin-bottom: 10px"></div>
    <?php
    if ($model->getQuestionsPassedCount() > 0) {
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