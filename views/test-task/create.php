<?php

use app\models\form\CreateTestTaskForm;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\widgets\ActiveForm;
use kozlovsv\crud\widgets\FormBuilder;
use kozlovsv\crud\widgets\ToolBarPanelContainer;

/* @var $this yii\web\View */
/* @var $model CreateTestTaskForm */

$this->title = 'Пройти тест';
?>
<div class="row">
    <div class="col-sm-6 col-sm-offset-3">
        <div class="jumbotron">
            <?php
                $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL]);
                echo Html::tag('h1', Html::encode($this->title), ['class' => 'form-header', 'style' => 'font-size: 25px']);
                echo FormBuilder::widget([
                    'form' => $form,
                    'model' => $model,
                    'attributes' => [
                        'letters' => [
                            'type' => FormBuilder::INPUT_CHECKBOX_LIST,
                            'items' => CreateTestTaskForm::mapLetters(),
                            'options' => ['class' => 'form-inline', 'style' => 'margin-bottom: 25px']
                        ],
                        'cnt_words' => [
                            'type' => FormBuilder::INPUT_DROPDOWN_LIST,
                            'items' => array_combine(CreateTestTaskForm::CNT_WORDS_RANGE, CreateTestTaskForm::CNT_WORDS_RANGE),
                        ],
                    ]
                ]);

            echo ToolBarPanelContainer::widget([
                    'buttonsRight' => [
                        CrudButton::cancelButton('Отмена', ['index'], $options = ['class' => 'btn btn-primary btn-lg form-cancel']),
                    ],
                    'buttonsLeft' => [
                        CrudButton::saveButton('Начать', $options = ['class' => 'btn btn-primary btn-lg']),
                    ],
                    'options' => ['class' => 'form-group', 'style' => 'margin: 20px 0 50px 0'],
                ]
            );
            ActiveForm::end();
            ?>
        </div>
    </div>
</div>

