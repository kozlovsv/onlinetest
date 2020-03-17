<?php

use app\models\TestTaskQuestion;
use app\models\User;
use app\models\VocabularyWord;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\widgets\DatePicker;
use kozlovsv\crud\widgets\FormBuilder;
use kozlovsv\crud\widgets\GridView;
use kozlovsv\crud\widgets\Pjax;
use kozlovsv\crud\widgets\SearchPanel;
use kozlovsv\crud\widgets\Select2;
use kozlovsv\crud\widgets\ToolBarPanel;
use kozlovsv\crud\helpers\ReturnUrl;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model VocabularyWord */
/* @var $searchModel app\models\search\ErrorAnswerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Html::encode($model->title) . ' - ошибочные ответы по слову';
$this->params['breadcrumbs'][] = ['label' => 'Статистика ошибочных ответов', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = Html::encode($model->title);

?>

<div class="test-task-view">
    <h1><?= CrudButton::cancelButton(Html::icon('arrow-left'), ['index']) . ' Ошибочные ответы по слову ' . Html::tag('span', Html::encode($model->title), ['style' => 'color:red']) ?></h1>
    <?php
    Pjax::begin([
        'id' => 'pjax-content',
        'formSelector' => false,
    ]);

    echo ToolBarPanel::widget(
        [
            'buttons' => [
                SearchPanel::widget([
                    'model' => $searchModel,
                    'formSearchConfig' => ['action' => ['view', 'id' => $model->id]],
                    'resetUrl' => ['view', 'id' => $model->id],
                    'attributes' => [
                        'user_id' => [
                            'type' => FormBuilder::INPUT_WIDGET,
                            'widgetClass' => Select2::class,
                            'options' => [
                                'data' => User::mapByRole(User::ROLE_STUDENT),
                                'pluginOptions' => ['minimumResultsForSearch' => 1]
                            ],
                        ],
                        'passed_at' => [
                            'type' => FormBuilder::INPUT_WIDGET,
                            'widgetClass' => DatePicker::class,
                        ],
                        'type' => [
                            'type' => FormBuilder::INPUT_WIDGET,
                            'widgetClass' => Select2::class,
                            'options' => [
                                'data' => TestTaskQuestion::typeMap(),
                                'pluginOptions' => ['minimumResultsForSearch' => -1]
                            ],
                        ],
                    ],
                ]),
            ]
        ]
    );
    ?>
    <?= GridView::widget(
            [
                'dataProvider' => $dataProvider,
                'actionColumnsBefore' => [],
                'actionColumnsAfter' => [],
                'columns' => [
                    [
                        'format' => 'raw',
                        'attribute' => 'test_task_id',
                        'value' => function ($model) {
                            return Html::a($model->test_task_id, ['/student-test-task/view', 'id' => $model->test_task_id, ReturnUrl::REQUEST_PARAM_NAME => Url::current()], ['data-pjax' => 0]);
                        },
                    ],
                    'passed_at:date:Пройден',
                    'user_name',
                    'answer',
                    'typeLabel'
                ],
            ]
        );
    ?>
    <?php Pjax::end(); ?>
</div>