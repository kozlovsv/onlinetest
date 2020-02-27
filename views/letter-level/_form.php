<?php

use app\models\Letter;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\widgets\ActiveForm;
use kozlovsv\crud\widgets\FormBuilder;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use kozlovsv\crud\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LetterLevel */

$form = ActiveForm::begin();
echo Html::tag('h1', Html::encode($this->title), ['class' => 'form-header']);
echo FormBuilder::widget([
        'form' => $form,
        'model' => $model,
        'attributes' => [
            'letter_id' => [
                'type' => FormBuilder::INPUT_DROPDOWN_LIST,
                'items' => Letter::map('id', 'title'),
                'visible' => $model->isNewRecord,
            ],
            'cnt_word_in_level',
            'cnt_level',
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