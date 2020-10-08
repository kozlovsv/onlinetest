<?php

use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\helpers\Html;
use kozlovsv\crud\helpers\ReturnUrl;
use kozlovsv\crud\widgets\ActiveForm;
use kozlovsv\crud\widgets\FormBuilder;
use kozlovsv\crud\widgets\Pjax;
use kozlovsv\crud\widgets\ToolBarPanelContainer;


/* @var $this yii\web\View */
/* @var $model app\models\form\RegistrationForm */

$this->title = 'Создать пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">
    <?php
    Pjax::begin(['id' => 'pjax-form']);
    $form = ActiveForm::begin();


    echo Html::tag('h1', Html::encode($this->title), ['class' => 'form-header']);
    echo FormBuilder::widget([
            'form' => $form,
            'model' => $model,
            'attributes' => [
                'login',
                'name',
                'email',
                'password' => [
                    'type' => FormBuilder::INPUT_PASSWORD
                ]
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
    Pjax::end();
    ?>
</div>
