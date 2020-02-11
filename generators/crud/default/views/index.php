<?php

/* @var $this yii\web\View */
/* @var $generator app\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\widgets\GridView;
use kozlovsv\crud\widgets\Pjax;
use kozlovsv\crud\widgets\SearchPanel;
use kozlovsv\crud\widgets\ToolBarPanel;


/* @var $this yii\web\View */
/* @var $searchModel <?= ltrim($generator->getSearchModelClass(), '\\') ?> */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '<?= $generator->moduleLabel ?>';
$this->params['breadcrumbs'][] = $this->title;

$isModal = <?= $generator->enableModal ? 'true' : 'false' ?>;

Pjax::begin([
    'id' => 'pjax-content',
    'formSelector' => false,
]);

echo ToolBarPanel::widget(
    [
        'buttons' => [
            CrudButton::createButton($searchModel::tableName(), $isModal),
            SearchPanel::widget([
                'model' => $searchModel,
                'attributes' => [
        <?php foreach ($generator->getSearchColumnNames() as $name) : ?>
            <?= "'". $name . "',\n"; ?>
        <?php endforeach; ?>        ],
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
<?php foreach ($generator->getColumnNames() as $name) : ?>
            <?= "'". $name . "',\n"; ?>
<?php endforeach; ?>
        ],
    ]
);

Pjax::end();