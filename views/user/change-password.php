<?php

use kozlovsv\crud\helpers\ReturnUrl;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\widgets\ActiveForm;
use kozlovsv\crud\widgets\FormBuilder;
use kozlovsv\crud\widgets\Pjax;
use kozlovsv\crud\widgets\ToolBarPanelContainer;
use kozlovsv\crud\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\form\ChangePasswordForm */

$this->title = 'Изменить пароль';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ReturnUrl::getBackUrl()];
$this->params['breadcrumbs'][] = ['label' => $model->getName(), 'url' => ['view', 'id' => $model->getId()]];
$this->params['breadcrumbs'][] = 'Изменить пароль';
?>

<div class="user-update">
    <?php
    Pjax::begin(['id' => 'pjax-form']);
    $form = ActiveForm::begin();


    echo Html::tag('h1', Html::encode($this->title), ['class' => 'form-header']);
    echo FormBuilder::widget([
            'form' => $form,
            'model' => $model,
            'attributes' => [
                'password:fa:user',
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