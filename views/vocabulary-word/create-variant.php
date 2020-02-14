<?php

use kozlovsv\crud\helpers\ReturnUrl;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\widgets\ActiveForm;
use kozlovsv\crud\widgets\FormBuilder;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use kozlovsv\crud\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\VocabularyWordVariant */


$this->title = 'Создать словарное слово';
$this->params['breadcrumbs'][] = ['label' => 'Словарные слова', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vocabulary-word-create">
    <?php
        $form = ActiveForm::begin();
        echo Html::tag('h1', Html::encode($this->title), ['class' => 'form-header']);
        echo FormBuilder::widget([
                'form' => $form,
                'model' => $model,
                'attributes' => [
                    'title:fa:file-word',
                    'variant1:fa:file-word',
                    'variant2:fa:file-word',
                    'variant3:fa:file-word',
                    'variant4:fa:file-word',
                    'variant5:fa:file-word',
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
    ?>
</div>
