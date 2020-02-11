<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator app\generators\crud\Generator */

echo $form->field($generator, 'basePath')->dropDownList([
    'app' => 'app',
    'frontend' => 'frontend',
    'backend' => 'backend',
]);
echo $form->field($generator, 'modelName');
echo $form->field($generator, 'moduleLabel');
echo $form->field($generator, 'modelLabel');
echo $form->field($generator, 'modelRLabel');
echo $form->field($generator, 'enableModal')->checkbox();
echo $form->field($generator, 'migrationPath');


