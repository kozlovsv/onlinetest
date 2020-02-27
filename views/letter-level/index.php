<?php

use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\widgets\GridView;
use kozlovsv\crud\widgets\Pjax;
use kozlovsv\crud\widgets\ToolBarPanel;


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\LetterLevelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Уровни букв';
$this->params['breadcrumbs'][] = $this->title;

$isModal = true;

Pjax::begin([
    'id' => 'pjax-content',
    'formSelector' => false,
]);

echo ToolBarPanel::widget(
    [
        'buttons' => [
            CrudButton::createButton($searchModel::tableName(), true, 'Заполнить', ['fill'], ['class' => 'btn btn-info', 'data-confirm' => 'Удалить все уровни и заполнить заново?']),
            CrudButton::createButton($searchModel::tableName(), $isModal),
        ]
    ]
);

echo GridView::widget(
    [
        'dataProvider' => $dataProvider,
        'isModal' => $isModal,
        'permissionCategory' => $searchModel::tableName(),
        'columns' => [
            'letter.title',
            'cnt_word_in_level',
            'cnt_level',
        ],
    ]
);

Pjax::end();