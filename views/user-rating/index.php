<?php

use app\models\search\UserRatingSearch;
use kozlovsv\crud\widgets\GridView;
use kozlovsv\crud\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ErrorAnswerStatisticSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Рейтинг учеников';
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin([
    'id' => 'pjax-content',
    'formSelector' => false,
]);

echo GridView::widget(
    [
        'dataProvider' => $dataProvider,
        'actionColumnsAfter' => [],
        'isModal' => false,
        'permissionCategory' => 'student_test_task',
        'columns' => [
            'name',
            'corona_cnt',
            'repetition_cnt',
            'average_rating:money',
        ],
        'rowOptions'=> /**
         * @param $model UserRatingSearch
         * @return array
         */ function($model){
            if ($model->averageGrade == 5) {
                return ['class' => 'success'];
            } elseif ($model->averageGrade == 4) {
                return ['class' => 'info'];
            }
            return ['class' => 'warning'];
        },
    ]
);
Pjax::end();