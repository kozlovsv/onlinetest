<?php
/* @var $this yii\web\View */

use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use yii\helpers\Html;

/* @var $model app\models\Log */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Логи приложения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="contragent-view">
    <h1><?= Html::encode($this->title) ?></h1>
<?php
echo ToolBarPanelContainer::widget(
    [
        'buttonsLeft' => [
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
                'value' => '<pre style="max-width: 600px; overflow-x: scroll">' . $model->message . '</pre>',
            ],
        ],
    ]
);
?>
</div>