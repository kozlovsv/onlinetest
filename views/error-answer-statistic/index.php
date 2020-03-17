<?php

use app\models\Letter;
use app\models\search\ErrorAnswerSearch;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\widgets\DatePicker;
use kozlovsv\crud\widgets\FormBuilder;
use kozlovsv\crud\widgets\GridView;
use kozlovsv\crud\widgets\Pjax;
use kozlovsv\crud\widgets\SearchPanel;
use kozlovsv\crud\widgets\Select2;
use kozlovsv\crud\widgets\ToolBarPanel;


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ErrorAnswerStatisticSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статистика ошибочных ответов';
$this->params['breadcrumbs'][] = $this->title;

$isModal = false;

Pjax::begin([
    'id' => 'pjax-content',
    'formSelector' => false,
]);

echo ToolBarPanel::widget(
    [
        'buttons' => [
           SearchPanel::widget([
                'model' => $searchModel,
                'attributes' => [
                    'letter_id' => [
                        'type' => FormBuilder::INPUT_WIDGET,
                        'widgetClass' => Select2::class,
                        'options' => [
                            'data' => Letter::map('id', 'title'),
                            'pluginOptions' => ['minimumResultsForSearch' => -1]
                        ],
                    ],
                    'passed_at' => [
                        'type' => FormBuilder::INPUT_WIDGET,
                        'widgetClass' => DatePicker::class,
                    ],
                ],
            ]),
        ]
    ]
);

$params = ErrorAnswerSearch::getSafeParams();

echo GridView::widget(
    [
        'dataProvider' => $dataProvider,
        'actionColumnsBefore' => [],
        'actionColumnsAfter' => [],
        'isModal' => $isModal,
        'permissionCategory' => 'student_test_task',
        'columns' => [
            [
                'format' => 'raw',
                'attribute' => 'word_title',
                'value' => function ($model) use ($params) {
                    return Html::a($model->word_title, array_merge(['view', 'id' => $model->id], $params), ['data-pjax' => 0]);
                },
            ],
            'cnt',
        ],
    ]
);
Pjax::end();