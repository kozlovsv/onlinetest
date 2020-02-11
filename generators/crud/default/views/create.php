<?php

use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $generator app\generators\crud\Generator */

echo "<?php\n";
?>

use kozlovsv\crud\helpers\ReturnUrl;


/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->getModelClass(), '\\') ?> */

$this->title = 'Создать <?= mb_strtolower($generator->modelRLabel ? $generator->modelRLabel : $generator->modelLabel) ?>';
$this->params['breadcrumbs'][] = ['label' => '<?= $generator->moduleLabel ?>', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id($generator->modelName) ?>-create">
    <?= "<?= " ?>$this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
