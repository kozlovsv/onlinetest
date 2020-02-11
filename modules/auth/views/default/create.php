<?php




/* @var $this yii\web\View */

use yii\helpers\Html;

/* @var $model app\modules\auth\models\AuthItem */

$this->title = 'Создать роль';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
