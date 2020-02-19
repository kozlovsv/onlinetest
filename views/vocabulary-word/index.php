<?php

use app\models\Letter;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\widgets\FormBuilder;
use kozlovsv\crud\widgets\GridView;
use kozlovsv\crud\widgets\Pjax;
use kozlovsv\crud\widgets\SearchPanel;
use kozlovsv\crud\widgets\Select2;
use kozlovsv\crud\widgets\ToolBarPanel;


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\VocabularyWordSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Словарные слова';
$this->params['breadcrumbs'][] = $this->title;

$isModal = true;

$this->registerJs(
    '$(document).keydown(function (e) {
        if (e.which == 45) {
            e.preventDefault();
            $( "#create-variant" ).click();
        }
    });'
);

Pjax::begin([
    'id' => 'pjax-content',
    'formSelector' => false,
]);

echo ToolBarPanel::widget(
    [
        'buttons' => [
            CrudButton::createButton($searchModel::tableName(), $isModal, 'Добавить с вариантами', ['create-variant'], ['id' => 'create-variant']),
            CrudButton::createButton($searchModel::tableName(), $isModal),
            SearchPanel::widget([
                'model' => $searchModel,
                'attributes' => [
                    'letter_id' => [
                        'type' => FormBuilder::INPUT_WIDGET,
                        'widgetClass' => Select2::class,
                        'options' => [
                            'data' => Letter::map('id', 'title'),
                        ],
                    ],
                    'title',
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
            'id',
            'letter.title',
            'title',
        ],
    ]
);

Pjax::end();