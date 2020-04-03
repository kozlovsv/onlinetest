<?php

use app\models\TestTask;
use kozlovsv\crud\widgets\DatePicker;
use kozlovsv\crud\widgets\FormBuilder;
use kozlovsv\crud\widgets\GridView;
use kozlovsv\crud\widgets\Pjax;
use kozlovsv\crud\widgets\SearchPanel;
use kozlovsv\crud\widgets\Select2;
use kozlovsv\crud\widgets\ToolBarPanel;
use app\helpers\TestTaskHtmlHelper;


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TestTaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мои тесты';
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
                    'grade' => [
                        'type' => FormBuilder::INPUT_WIDGET,
                        'widgetClass' => Select2::class,
                        'options' => [
                            'data' => array_combine(TestTask::gradeList(), TestTask::gradeList()),
                            'pluginOptions' => ['minimumResultsForSearch' => -1]
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
        'permissionCategory' => $searchModel::tableName(),
        'columns' => [
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
                }
                return ['class' => TestTaskHtmlHelper::getGradeColor($model->grade)];
         },
    ]
);

Pjax::end();