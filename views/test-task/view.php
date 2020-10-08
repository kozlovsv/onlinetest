<?php /** @noinspection DuplicatedCode */

use app\helpers\TestTaskHtmlHelper;
use app\models\TestTask;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\widgets\GridView;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use yii\bootstrap\Progress;
use yii\data\ArrayDataProvider;
use kozlovsv\crud\helpers\ReturnUrl;


/* @var $this yii\web\View */
/* @var $model TestTask */

$this->title = "Тест № {$model->id}";
$this->params['breadcrumbs'][] = ['label' => 'Мои тесты', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = $this->title;

$isModal = false;

$gradeClass = TestTaskHtmlHelper::getGradeColor($model->getGrade());
$statusClass = ($model->status == TestTask::STATUS_NEW) ? 'warning' : 'success';
$questionsStayCountClass = $model->getQuestionsTestStayCount() <> 0 ? 'danger' : '';
?>
<div class="test-task-view">
    <h1><?= Html::encode("$this->title ") ?></h1>
    <?php
    echo ToolBarPanelContainer::widget(
        [
            'buttonsLeft' => [
                CrudButton::cancelButton(Html::icon('arrow-left'), ['/']),
                $model->is_repetition ? Html::a('Тест', ['re-test', 'id' => $model->id], ['class' => 'btn btn-info', 'data' => ['confirm' => 'Пройти тест заново? Все ранее веденные ответы пропадут.']]) : '',
                Html::a('Слова', ['training-test', 'id' => $model->id], ['class' => 'btn btn-success', 'data' => ['modal' => 1]])
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
                TestTaskHtmlHelper::getTestStatusAttribute($model, $statusClass),
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
                    'resultLabel',
                    'typeLabel'
                ],
                'rowOptions' => function ($model) {
                    return $model->result ? ['class' => 'success'] : ['class' => 'danger'];
                },
            ]
        );
    }

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

</div>