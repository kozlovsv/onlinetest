<?php

use app\models\TestTask;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\widgets\GridView;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use yii\bootstrap\Progress;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use kozlovsv\crud\helpers\ReturnUrl;


/* @var $this yii\web\View */
/* @var $model TestTask */

$this->title = "Тест № {$model->id}";
$this->params['breadcrumbs'][] = ['label' => 'Пройденные тесты', 'url' => ReturnUrl::getBackUrl()];
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
$questionsStayCountClass = $model->getQuestionsStayCount() <> 0 ? 'danger' : '';

?>
<div class="test-task-view">
    <h1><?= Html::encode("$this->title ") ?></h1>
    <?php
    echo ToolBarPanelContainer::widget(
        [
            'buttonsLeft' => [
                CrudButton::cancelButton('Назад'),
                $model->canTestContinue() ? Html::a('Продолжить', ['next', 'id' => $model->id], ['class' => 'btn btn-success']) : '',
                $model->canTestRePass() ? Html::a('Пройти заново', ['repass', 'id' => $model->id], ['class' => 'btn btn-warning', 'data' => ['confirm' => 'Пройти тест заново? Все ранее веденные ответы пропадут.']]) : '',
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
                'statusLabel',
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