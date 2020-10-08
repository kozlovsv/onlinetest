<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model LoginForm */

use app\models\form\LoginForm;
use kozlovsv\crud\helpers\CrudButton;
use kozlovsv\crud\widgets\ActiveForm;
use kozlovsv\crud\widgets\FormBuilder;
use kozlovsv\crud\widgets\Pjax;
use kozlovsv\crud\widgets\ToolBarPanelContainer;

$this->title = 'Регистрация на ' . Yii::$app->params['brand'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-lg-offset-3">
                <h1>Регистрация в системе</h1>
                <?php
                Pjax::begin(['id' => 'pjax-form']);
                $form = ActiveForm::begin(
                    [
                        'options' => [
                            'id' => 'registration-form',
                        ],
                    ]
                );
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
                        'buttonsLeft' => [
                            CrudButton::saveButton('Зарегистрироваться'),
                        ],
                        'buttonsRight' => [
                            CrudButton::cancelButton(),
                        ],
                        'options' => ['class' => 'form-group', 'style' => 'margin-top: 20px; margin-right: 0; margin-left: 0'],
                    ]
                );
                ActiveForm::end();
                Pjax::end();
                ?>
            </div>
        </div>
    </div>
</div>