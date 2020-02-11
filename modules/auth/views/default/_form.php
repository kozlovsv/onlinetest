<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\auth\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */
?>
<?php Pjax::begin(); ?>
    <?php $form = ActiveForm::begin([
        'action' => 'create',
        'layout' => 'horizontal',
        'options' => [
            'data-pjax' => 1,
        ],
    ]); ?>
        <?= $form->field($model, 'description')->textInput() ?>
        <div class="text-right">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
<?php Pjax::end() ?>

