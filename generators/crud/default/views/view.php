<?php

/* @var $this yii\web\View */

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $generator app\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use yii\helpers\Html;
use kozlovsv\crud\helpers\ReturnUrl;


/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->getModelClass(), '\\') ?> */

$this->title = $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => '<?= $generator->moduleLabel ?>', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = $this->title;

$isModal = <?= $generator->enableModal ? 'true' : 'false' ?>;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->getModelClass())) ?>-view">
    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>
    <?php    echo "<?php\n"; ?>
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
    <?php    echo "<?php\n"; ?>
    echo yii\widgets\DetailView::widget(
        [
            'model' => $model,
            'attributes' => [
        <?php foreach ($generator->getColumnNames() as $name) : ?>
        <?= "'" . $name . "',\n"; ?>
        <?php endforeach; ?>
    ],
        ]
    );
    ?>
</div>