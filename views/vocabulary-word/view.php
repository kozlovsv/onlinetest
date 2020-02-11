<?php

use app\models\search\VocabularyWordVariantSearch;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\helpers\ModelPermission;
use kozlovsv\crud\widgets\ActionColumn;
use kozlovsv\crud\widgets\GridView;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use kozlovsv\crud\helpers\ReturnUrl;

/* @var $this yii\web\View */
/* @var $model app\models\VocabularyWord */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Словарные слова', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = $this->title;

$isModal = true;
?>
<div class="vocabulary-word-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    echo ToolBarPanelContainer::widget(
        [
            'buttonsLeft' => [
                CrudButton::editButton($model::tableName(), $model->getPrimaryKey(), $isModal),
                CrudButton::cancelButton('Закрыть'),
            ],
            'buttonsRight' => [
                CrudButton::deleteButton($model::tableName(), $model->getPrimaryKey()),
            ],
            'options' => ['class' => 'form-group', 'style' => 'margin-bottom: 10px'],
        ]
    );
    ?>
    <div class="clearfix"></div>
    <?php
    echo yii\widgets\DetailView::widget(
        [
            'model' => $model,
            'attributes' => [
                'letter.title',
                'title',
            ],
        ]
    );
    ?>
    <div class="clearfix" style="margin-bottom: 10px"></div>
    <h1>Варианты неправильного написания:</h1>
    <?php
    $permitionCategory = 'vocabulary_word';
    $vocabularyWordVariantSearch = new VocabularyWordVariantSearch();
    $vocabularyWordVariantSearch->vocabulary_word_id = $model->id;
    echo CrudButton::createButton($permitionCategory, $isModal, 'Добавить вариант', ['vocabulary-word-variant/create', 'uid' => $model->id]);
    echo GridView::widget(
        [
            'dataProvider' => $vocabularyWordVariantSearch->search([]),
            'permissionCategory' => $permitionCategory,
            'layout' => '{items}',
            'actionColumnsBefore' => [],
            'actionColumnsAfter' => [
                [
                    'class' => ActionColumn::class,
                    'template' => '{update}',
                    'isModal' => $isModal,
                    'visible' => ModelPermission::canUpdate($permitionCategory),
                        'controller' => 'vocabulary-word-variant'
                ],
                [
                    'class' => ActionColumn::class,
                    'template' => '{delete}',
                    'visible' => ModelPermission::canDelete($permitionCategory),
                    'controller' => 'vocabulary-word-variant',
                    'buttons' => [
                        'delete' => function ($url) {
                            $options = [
                                'title' => 'Удалить',
                                'aria-label' => 'Удалить',
                                'data-pjax' => 0,
                                'data-modal' => 1,
                            ];
                            return Html::a(Html::icon('remove'), $url, $options);
                        }
                    ],
                ],
            ],
            'columns' => [
                'title',
            ],
        ]
    );
    ?>
</div>