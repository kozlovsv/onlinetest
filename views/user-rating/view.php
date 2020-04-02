<?php

use app\models\search\UserRatingViewSearch;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\widgets\GridView;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use kozlovsv\crud\helpers\ReturnUrl;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Рейтинг учеников', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = $this->title;

$isModal = true;
//Вызываем создание этой модели тут, чотбы не переопределять стандартный CRUD метод View. Да я знаю что так нельзя!!!
$userRatingViewSearch = new UserRatingViewSearch();
$userRatingListDataProvider = $userRatingViewSearch->search($model->id);

?>
<div class="user-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    echo ToolBarPanelContainer::widget(
        [
            'buttonsLeft' => [
                CrudButton::cancelButton('Назад'),
                Html::a('Тесты', ['/student-test-task', 'StudentTestTaskSearch[user_id]' => $model->id], ['class' => ['btn', 'btn-primary']]),
            ],
            'buttonsRight' => [
            ],
            'options' => ['class' => 'form-group', 'style' => 'margin-bottom: 10px'],
        ]
    );
    ?>
    <div class="clearfix"></div>
    <?= yii\widgets\DetailView::widget(
        [
            'model' => $model,
            'attributes' => [
                'userAchievementsCount:text:Количество корон',
                'testTaskRepetitionCount:text:Количество контрольных',
                'averageRating:money:Средний рейтинг',
                'averageGrade:text:Средняя оценка',
            ],
        ]
    );
    ?>
    <div style="margin-bottom: 10px">
        <?= GridView::widget(
            [
                'dataProvider' => $userRatingListDataProvider,
                'layout' => '{items}',
                'actionColumnsBefore' => [],
                'actionColumnsAfter' => [],
                'columns' => [
                    'title',
                    [
                        'attribute' => 'corona_cnt',
                        'contentOptions' => function ($data) {
                            /** @var UserRatingViewSearch $data */
                            return ['class' => $data->getLevelIsFull()? 'level-full ': ''];
                        },
                    ],
                    'repetition_cnt',
                    'average_rating:money',
                ],
                'rowOptions' => /**
                 * @param $model UserRatingViewSearch
                 * @return array
                 */ function ($model) {
                    if ($model->averageGrade == 5) {
                        return ['class' => 'success'];
                    } elseif ($model->averageGrade == 4) {
                        return ['class' => 'info'];
                    }
                    return ['class' => 'warning'];
                },
            ]
        );
        ?>
    </div>
</div>