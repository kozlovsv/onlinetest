<?php

use app\models\Letter;
use app\models\TestTask;
use app\models\User;
use kozlovsv\crud\widgets\DatePicker;
use kozlovsv\crud\widgets\FormBuilder;
use kozlovsv\crud\widgets\GridView;
use kozlovsv\crud\widgets\Pjax;
use kozlovsv\crud\widgets\SearchPanel;
use kozlovsv\crud\widgets\Select2;
use kozlovsv\crud\widgets\ToolBarPanel;


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\StudentTestTaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Тесты учеников';
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
                    'user_id' => [
                        'type' => FormBuilder::INPUT_WIDGET,
                        'widgetClass' => Select2::class,
                        'options' => [
                            'data' => User::mapByRole(User::ROLE_STUDENT),
                            'pluginOptions' => ['minimumResultsForSearch' => 1]
                        ],
                    ],
                    'status' => [
                        'type' => FormBuilder::INPUT_WIDGET,
                        'widgetClass' => Select2::class,
                        'options' => [
                            'data' => TestTask::statusMap(),
                            'pluginOptions' => ['minimumResultsForSearch' => -1]
                        ],
                    ],
                    'is_repetition' => [
                        'type' => FormBuilder::INPUT_WIDGET,
                        'widgetClass' => Select2::class,
                        'options' => [
                            'data' => TestTask::isRepetitionMap(),
                            'pluginOptions' => ['minimumResultsForSearch' => -1]
                        ],
                    ],
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

echo GridView::widget(
    [
        'dataProvider' => $dataProvider,
        'isModal' => $isModal,
        'permissionCategory' => 'student_test_task',
        'columns' => [
            'user.name',
            'statusLabel',
            'letter.title',
            'grade',
            'passed_at:datetime',
        ],
        'rowOptions'=> /**
         * @param $model TestTask
         * @return array
         */ function($model){
                if($model->status == TestTask::STATUS_NEW){
                    return ['class' => 'danger'];
                } elseif ($model->grade == 5) {
                    return ['class' => 'success'];
                } elseif ($model->grade == 4) {
                    return ['class' => 'info'];
                }
                return ['class' => 'warning'];
         },
    ]
);

Pjax::end();