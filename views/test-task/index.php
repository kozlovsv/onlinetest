<?php

use app\models\TestTask;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\widgets\DatePicker;
use kozlovsv\crud\widgets\FormBuilder;
use kozlovsv\crud\widgets\GridView;
use kozlovsv\crud\widgets\Pjax;
use kozlovsv\crud\widgets\SearchPanel;
use kozlovsv\crud\widgets\Select2;
use kozlovsv\crud\widgets\ToolBarPanel;


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\TestTaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пройденные тесты';
$this->params['breadcrumbs'][] = $this->title;

$isModal = false;

Pjax::begin([
    'id' => 'pjax-content',
    'formSelector' => false,
]);

echo ToolBarPanel::widget(
    [
        'buttons' => [
            CrudButton::createButton($searchModel::tableName(), $isModal, 'Пройти новый тест'),
            SearchPanel::widget([
                'model' => $searchModel,
                'attributes' => [
                    'status' => [
                        'type' => FormBuilder::INPUT_WIDGET,
                        'widgetClass' => Select2::class,
                        'options' => [
                            'data' => TestTask::statusMap(),
                            'pluginOptions' => ['minimumResultsForSearch' => -1]
                        ],
                    ],
                    'created_at' => [
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
            'created_at:date',
        ],
    ]
);

Pjax::end();