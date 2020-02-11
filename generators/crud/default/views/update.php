<?php

use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $generator app\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();

echo "<?php\n";
?>

use kozlovsv\crud\helpers\ReturnUrl;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->getModelClass(), '\\') ?> */

$this->title = 'Изменить <?= mb_strtolower($generator->modelRLabel ? $generator->modelRLabel : $generator->modelLabel) ?>: ' . $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => '<?= $generator->moduleLabel ?>', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = ['label' => $model-><?= $generator->getNameAttribute() ?>, 'url' => ['view', <?= $urlParams ?>]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="<?= Inflector::camel2id($generator->modelName) ?>-update">
    <?= "<?= " ?>$this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
