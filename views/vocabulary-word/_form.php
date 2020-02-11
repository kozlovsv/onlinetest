<?php

use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\widgets\ActiveForm;
use kozlovsv\crud\widgets\FormBuilder;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use kozlovsv\crud\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\VocabularyWord */

$form = ActiveForm::begin();
echo Html::tag('h1', Html::encode($this->title), ['class' => 'form-header']);
echo FormBuilder::widget([
        'form' => $form,
        'model' => $model,
        'attributes' => [
            'title:fa:file-word',
        ]
    ]
);

echo ToolBarPanelContainer::widget([
        'buttonsRight' => [
            CrudButton::saveButton(),
            CrudButton::cancelButton(),
        ],
        'options' => ['class' => 'form-group', 'style' => 'margin-top: 20px; margin-right: 0'],
    ]
);

ActiveForm::end();