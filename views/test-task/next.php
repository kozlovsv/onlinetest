<?php


use app\models\form\ChooseAnswerForm;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\widgets\ActiveForm;
use kozlovsv\crud\widgets\FormBuilder;
use kozlovsv\crud\widgets\ToolBarPanelContainer;

/* @var $this yii\web\View */
/* @var $model ChooseAnswerForm */

$this->title = 'Как пишется правильно';
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
                        'choice' => [
                            'type' => FormBuilder::INPUT_RADIO_LIST,
                            'items' => $model->mapQuesions(),
                            'options' => ['style' => 'margin-bottom: 25px']
                        ],
                    ]
                ]);

            echo ToolBarPanelContainer::widget([
                    'buttonsRight' => [
                        CrudButton::cancelButton('Закончить тест', ['index'], $options = ['class' => 'btn btn-primary btn-lg form-cancel']),
                    ],
                    'buttonsLeft' => [
                        CrudButton::saveButton('Ответить', $options = ['class' => 'btn btn-primary btn-lg']),
                    ],
                    'options' => ['class' => 'form-group', 'style' => 'margin: 20px 0 50px 0'],
                ]
            );
            ActiveForm::end();
            ?>
        </div>
    </div>
</div>

