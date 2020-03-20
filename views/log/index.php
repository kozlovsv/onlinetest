<?php

use app\models\Log;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\widgets\ActionColumn;
use kozlovsv\crud\helpers\ModelPermission;
use kozlovsv\crud\widgets\FormBuilder;
use kozlovsv\crud\widgets\GridView;
use kozlovsv\crud\widgets\Pjax;
use kozlovsv\crud\widgets\SearchPanel;
use kozlovsv\crud\widgets\Select2;
use kozlovsv\crud\widgets\ToolBarPanel;


/* @var $this yii\web\View */
/* @var $searchModel app\models\search\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Логи приложения';
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin([
    'id' => 'pjax-content',
    'formSelector' => false,
]);

echo ToolBarPanel::widget(
    [
        'buttons' => [
            Html::a('Удалить пустые тесты', ['delete-empty-test'], ['class' => ['btn', 'btn-danger'], 'data' => ['confirm' => 'Удалить пустые тесты?']]),
            SearchPanel::widget([
                'model' => $searchModel,
                'attributes' => [
                    'level' => [
                        'type' => FormBuilder::INPUT_WIDGET,
                        'widgetClass' => Select2::class,
                        'options' => [
                            'data' => Log::levelMap(),
                            'pluginOptions' => ['minimumResultsForSearch' => -1]
                        ],
                    ],
                    'category' => [
                        'type' => FormBuilder::INPUT_WIDGET,
                        'widgetClass' => Select2::class,
                        'options' => [
                            'data' => Log::categoryMap(),
                            'pluginOptions' => ['minimumResultsForSearch' => -1],
                        ],
                    ],
                    'message',
                ],
            ]),
        ]
    ]
);

echo GridView::widget(
    [
        'dataProvider' => $dataProvider,
        'isModal' => true,
        'permissionCategory' => $searchModel::tableName(),
        'columns' => [
            [
                'class' => yii\grid\CheckboxColumn::class,
                'checkboxOptions' => []

            ],
            'id',
            'levelLabel',
            'categoryLabel',
            [
                'attribute' => 'log_time',
                'format' => 'datetime',
            ],
            [
                'attribute' => 'message',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<pre style="max-height: 100px; overflow: auto;">' . $model->message . '</pre>';
                },
            ],
        ],
        'actionColumnsAfter' => [
            [
                'class' => ActionColumn::class,
                'template' => '{delete}',
                'visible' => ModelPermission::canDelete($searchModel::tableName()),
                'header' => Html::a(Html::icon('remove'), ['#'], ['title' => 'Удалить', 'aria-label' => 'Удалить', 'id' => 'delete-all-button']),
                'headerOptions' => ['class' => 'action-column action-header-button'],
            ],
        ]
    ]
);

//Pjax::end();

$js = <<<JS
        var lotListId = [];  
        $('body').on('click', '#delete-all-button', function(event) { 
            ids = [];
            $('input[name="selection[]"]:checked ').each(function() {
                ids.push(this.value); 
            });  
            if(ids.length==0)
            {
                return false;
            }
            if (!confirm('Удалить выделенные записи?')) return false;
           $.ajax({
                url: '/log/delete-all' ,
                type: 'post',
                data: {
                    ids:  ids                             
                },
                success: function (data) {
                }
            });
            location.reload();
            return false;
        });
JS;

$this->registerJs($js, yii\web\View::POS_END);